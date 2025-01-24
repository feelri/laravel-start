<?php

namespace App\Models\Admin;

use App\Models\Model;
use App\Models\Permission\Permission;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
	use SoftDeletes;

	/**
	 * 关联管理员
	 * @return BelongsToMany
	 */
	public function admins(): BelongsToMany
	{
		return $this->belongsToMany(Admin::class, 'admin_role', 'role_id', 'admin_id');
	}

	/**
	 * 关联角色权限
	 * @return BelongsToMany
	 */
	public function permissions(): BelongsToMany
	{
		return $this->belongsToMany(Permission::class, 'role_permission', 'role_id', 'permission_id');
	}
}
