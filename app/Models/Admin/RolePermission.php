<?php

namespace App\Models\Admin;

use App\Models\Model;
use App\Models\Permission\Permission;
use App\Traits\Model\ModelTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RolePermission extends Model
{
	use ModelTrait;

	public $timestamps = false;

	/**
	 * 权限
	 * @return BelongsTo
	 */
	public function permission(): BelongsTo
	{
		return $this->belongsTo(Permission::class, 'permission_id', 'id');
	}
}
