<?php

namespace App\Models;

use DateTimeInterface;
use App\Enums\Model\FileUploadFromEnum;
use App\Traits\Model\ModelTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Config;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\NewAccessToken;

class Authenticatable extends User
{
	use ModelTrait;
	use HasApiTokens;

	public NewAccessToken $newAccessToken;

	/**
	 * avatar 获取/访问器
	 *
	 * @return Attribute
	 */
	public function avatar(): Attribute
	{
		return new Attribute(
			get: function ($value)  {
				$value = $value ?? Config::get('app.avatar');
				$driver = Config::get('app.file_upload_driver');
				$baseUrl = match ($driver) {
					FileUploadFromEnum::QiNiu->value => Config::get('qi-niu.koDo.staticUrl'),
					FileUploadFromEnum::AliYun->value => Config::get('ali-yun.OSS.staticUrl'),
					default => Config::get('app.asset_url'),
				};
				return  trim($baseUrl, '/') . '/' . trim($value, '/');
			}
		);
	}

	/**
	 * 重新父级方法 createToken
	 *
	 * @param string                 $name
	 * @param array                  $abilities
	 * @param DateTimeInterface|null $expiresAt
	 * @return NewAccessToken
	 */
	public function createToken(string $name, array $abilities = ['*'], ?DateTimeInterface $expiresAt = null): NewAccessToken
	{
		$plainTextToken = $this->generateTokenString();
		$expiration = Config::get('sanctum.expiration');
		$token = $this->tokens()->create([
			'name' => $name,
			'token' => hash('sha256', $plainTextToken),
			'abilities' => $abilities,
			'expires_at' => $expiresAt ?? ($expiration ? now()->addMinutes($expiration) : null),
		]);

		$this->newAccessToken = new NewAccessToken($token, $token->getKey().'|'.$plainTextToken);
		return $this->newAccessToken;
	}

	/**
	 * 刷新 token
	 *
	 * @param DateTimeInterface|null $expiresAt
	 * @return NewAccessToken
	 */
	public function refreshCurrentToken(?DateTimeInterface $expiresAt = null): NewAccessToken
	{
		$token = $this->currentAccessToken();
		$expiration = Config::get('sanctum.expiration');
		$token->expires_at = $expiresAt ?? ($expiration ? now()->addMinutes($expiration) : null);
		$token->save();
		$plainTextToken = $this->generateTokenString();
		$this->newAccessToken = new NewAccessToken($token, $token->getKey().'|'.$plainTextToken);
		return $this->newAccessToken;
	}
}
