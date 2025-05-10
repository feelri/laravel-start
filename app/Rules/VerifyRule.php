<?php

namespace App\Rules;

use App\Enums\Model\ConfigKeyEnum;
use App\Services\Model\ConfigService;
use App\Services\ToolService;
use \Closure;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Mews\Captcha\Facades\Captcha;


/**
 * 人机验证
 * @package App\Rules
 */
class VerifyRule implements DataAwareRule, ValidationRule
{
	/**
	 * 所有正在验证的数据。
	 *
	 * @var array<string, mixed>
	 */
	protected array $data = [];

	/**
	 * 验证规则的参数。
	 *
	 * @var array<string, mixed>
	 */
	protected mixed $parameters = [];

	public function __construct(array $parameters = [])
	{
		$this->parameters = $parameters;
	}

	/**
	 * 设置正在验证的数据。
	 *
	 * @param  array<string, mixed>  $data
	 */
	public function setData(array $data): static
	{
		$this->data = $data;

		return $this;
	}

	/**
	 * 验证
	 *
	 * @param string  $attribute
	 * @param mixed   $value
	 * @param Closure $fail
	 * @return void
	 */
	public function validate(string $attribute, mixed $value, Closure $fail): void
	{
		if (empty($value)) {
			$fail(__('validation.verify'));
			return;
		}

		// 返回字符串 captcha
		$driver = empty($this->parameters[0])
			? ConfigService::static()->key(ConfigKeyEnum::System)->get('captcha')
			: $this->parameters[0];
		if (!method_exists($this, $driver)) {
			$fail("Method {$driver} does not exist.");
			return;
		}
		$this->$driver($value, $fail);
	}

	/**
	 * cloudflare 人机验证
	 *
	 * @param string  $token
	 * @param Closure $fail
	 * @return void
	 * @throws GuzzleException
	 */
	private function cloudflare(mixed $token, Closure $fail): void
	{
		$client = new Client();
		$response = $client->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
			'json'  => [
				'secret'   => Config::get('cloudflare.turnstile.secret'),
				'response' => $token
			]
		]);
		$body = $response->getBody()->getContents();
		if (!json_validate($body)) {
			$fail(__('validation.verify'));
		}
		$result = json_decode($body, true);

		if (!$result['success']) {
			$fail(__('validation.verify'));
		}
	}

	/**
	 * 图形验证码
	 *
	 * @param mixed   $token
	 * @param Closure $fail
	 * @return void
	 */
	private function captcha(mixed $token, Closure $fail): void
	{
		[$key, $value] = ToolService::static()->ignoreException(function () use ($token, $fail) {
			[$key, $value] = explode('--', $token);
			if (empty($key) || empty($value)) {
				$fail(__('validation.verify'));
			}
			return [$key, $value];
		});

		// 验证码验证
		$check = Captcha::check_api($value, $key, config('captcha.driver'));
		if (!$check) {
			$fail(__('validation.verify'));
		}
	}

	/**
	 * 短信验证
	 *
	 * @param mixed   $code
	 * @param Closure $fail
	 * @return void
	 */
	private function sms(mixed $code, Closure $fail): void
	{
		$type = $this->parameters[1];
		$target = empty($this->parameters[1]) ? 'mobile' : $this->parameters[1];
		$cacheCode = Cache::get("sms:$type:{$this->data[$target]}"); // 15分钟

		if ($cacheCode !== $code) {
			$fail(__('validation.verify'));
		}
	}
}
