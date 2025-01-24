<?php

namespace App\Models\Permission;

use App\Enums\Model\PermissionTypeEnum;
use App\Models\Model;
use App\Traits\Model\ModelTrait;
use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
	use NodeTrait, ModelTrait, SoftDeletes;

	protected $appends = [
		'type_label', 'permission_code'
	];

	protected $hidden = ['left', 'right', 'created_at', 'updated_at'];

	public function getLftName(): string
	{
		return 'left';
	}

	public function getRgtName(): string
	{
		return 'right';
	}

	public function getParentIdName(): string
	{
		return 'parent_id';
	}

	/**
	 * type_label 获取/访问器
	 * @return Attribute
	 */
	public function typeLabel(): Attribute
	{
		return new Attribute(
			get: fn () => PermissionTypeEnum::tryFrom($this->type)?->label() ?? ''
		);
	}

	/**
	 * permission_code 获取/访问器
	 * @return Attribute
	 */
	public function permissionCode(): Attribute
	{
		return new Attribute(
			get: fn () => "{$this->uri}:{$this->method}"
		);
	}

	/**
	 * component 获取/访问器
	 * @return Attribute
	 */
	public function component(): Attribute
	{
		return new Attribute(
			get: fn ($value) => $this->type === PermissionTypeEnum::Menu->value && empty($value) ? 'BasicLayout' : $value,
			set: function ($value) {
				if (empty($value)) {
					return 'BasicLayout';
				}
				return $value === 'BasicLayout' ? $value : '/' . ltrim($value, '/');
			}
		);
	}

	/**
	 * path 获取/访问器
	 * @return Attribute
	 */
	public function path(): Attribute
	{
		return new Attribute(
			set: fn ($value) => empty($value) ? '' : '/' . ltrim($value, '/')
		);
	}

	/**
	 * uri 获取/访问器
	 * @return Attribute
	 */
	public function uri(): Attribute
	{
		return new Attribute(
			set: fn ($value) => ltrim($value, '/')
		);
	}

	/**
	 * parent_id 获取/访问器
	 * @return Attribute
	 */
	public function parentId(): Attribute
	{
		return new Attribute(
			get: fn ($value) => empty($value) ? null : $value
		);
	}

	/**
	 * meta 获取/访问器
	 * @return Attribute
	 */
	public function meta(): Attribute
	{
		return new Attribute(
			get: fn () => ['icon'=>$this->icon, 'title'=>$this->name]
		);
	}

	/**
	 * 获取下级
	 * @return HasMany
	 */
	public function children(): HasMany
	{
		return $this->hasMany(Permission::class, 'parent_id', 'id');
	}

	/**
	 * 获取上级
	 * @return BelongsTo
	 */
	public function parent(): BelongsTo
	{
		return $this->belongsTo(Permission::class, 'parent_id', 'id');
	}
}
