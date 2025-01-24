<?php

namespace App\Models\Admin;

use App\Enums\BoolIntEnum;
use App\Enums\Model\ConfigKeyEnum;
use App\Models\Authenticatable;
use App\Services\Model\ConfigService;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
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

	protected $appends = [
		'is_disable_label'
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
	 * avatar 获取/访问器
	 * @return Attribute
	 */
	public function isDisableLabel(): Attribute
	{
		return new Attribute(
			get: function () {
				return BoolIntEnum::tryFrom($this->is_disable)->label() ?? '';
			}
		);
	}

	/**
	 * 是否包含最高权限角色
	 * @return bool
	 */
	public function hasTopLevelRole(): bool
	{
		$hasTopLevelRole = false;
		foreach ($this->roles as $role) {
			if ((int) $role->is_top_level === 1) {
				$hasTopLevelRole = true;
				break;
			}
		}
		return $hasTopLevelRole;
	}

	/**
	 * 关联用户角色
	 * @return BelongsToMany
	 */
	public function roles(): BelongsToMany
	{
		return $this->belongsToMany(Role::class, 'admin_role', 'admin_id', 'role_id');
	}
}
