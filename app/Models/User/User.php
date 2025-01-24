<?php

namespace App\Models\User;

use App\Enums\Model\ConfigKeyEnum;
use App\Models\Authenticatable;
use App\Services\Model\ConfigService;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Config;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * 类型转换
     * @return string[]
     */
    public function casts(): array
    {
        return [
            'id'            => 'integer',
            'account'       => 'string',
            'mobile'        => 'string',
            'email'         => 'string',
            'password'      => 'hashed',
            'name'          => 'string',
            'nickname'      => 'string',
            'avatar'        => 'string',
            'last_login_at' => 'datetime:Y-m-d H:i:s',
            'created_at'    => 'datetime:Y-m-d H:i:s',
            'updated_at'    => 'datetime:Y-m-d H:i:s',
        ];
    }

    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password', 'deleted_at'
    ];

	/**
	 * avatar 获取/访问器
	 * @return Attribute
	 */
	public function avatar(): Attribute
	{
		$service = ConfigService::static()->key(ConfigKeyEnum::System);
		return new Attribute(
			get: function ($value) use ($service) {
				$url = empty($value) ? $service->get('avatar') : $value;
				return $service->get('asset_url') . '/' . ltrim($url, '/');
			},
			set: function ($value) use ($service) {
				return str_replace($service->get('asset_url'), '', $value);
			}
		);
	}

	/**
	 * 创建默认用户
	 *
	 * @param array $data
	 * @return User
	 */
	public function createDefault(array $data = []): User
	{
		$service = ConfigService::static()->key(ConfigKeyEnum::UserIncrement);
		$incrementId = $service->get('id');
		if (!$incrementId) {
			$incrementId = rand(10000, 99999);
			$service->set('id', $incrementId);
		}
		$prefixName = strtolower(Config::get('app.name'));
		$createData = [
			'account' => "{$prefixName}_{$incrementId}",
			...$data
		];
		return $this->query()->create($createData);
	}

	/**
	 * 关联第三方授权
	 * @return HasMany
	 */
	public function oauth(): HasMany
	{
		return $this->hasMany(UserOauth::class);
	}
}
