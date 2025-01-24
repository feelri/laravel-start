<?php

namespace App\Services\Model;

use App\Enums\BoolIntEnum;
use App\Enums\Model\PermissionTypeEnum;
use App\Exceptions\ForbiddenException;
use App\Models\Admin\Admin;
use App\Models\Permission\Permission;
use App\Services\ToolService;
use Illuminate\Support\Facades\Cache;

/**
 * 权限菜单服务
 */
class PermissionService
{
	public static string $cachePrefix = 'permissions';

	/**
	 * 管理员
	 * @var Admin
	 */
	public Admin $admin;

	/**
	 * 设置管理员
	 *
	 * @param Admin $admin
	 * @return $this
	 */
	public function from(Admin $admin): static
	{
		$this->admin = $admin;
		return $this;
	}

	/**
	 * 是否拥有最高权限角色
	 *
	 * @return bool
	 * @throws ForbiddenException
	 */
	public function hasTopLevelRole(): bool
	{
		$admin = $this->admin;
		$admin->load([
			'roles' => function ($query) {
				$query->orderBy('is_top_level', 'desc');
			},
			'roles.permissions'
		]);
		if (empty($admin->roles)) {
			throw new ForbiddenException(__("messages.not_access_permission"));
		}

		$this->admin = $admin;

		return $admin->hasTopLevelRole();
	}

	/**
	 * 用户所有权限菜单
	 *
	 * @return array
	 * @throws ForbiddenException
	 */
	public function getAllPermissions(): array
	{
		$hasTopLevelRole = $this->hasTopLevelRole();

		// 用户权限
		if (!$hasTopLevelRole) {
			$allPermissions = $this->allPermissions();
			return [
				'menus'       => ToolService::static()->tree(array_values($allPermissions['menus']), 'id', 'parent_id'),
				'permissions' => $allPermissions['permissions']
			];
		}

		// 所有权限
		$menus       = $this->cacheResult(PermissionTypeEnum::Menu);
		$permissions = $this->cacheResult(PermissionTypeEnum::Permission);
		return [
			'menus'       => $menus,
			'permissions' => $permissions
		];
	}

	/**
	 * 获取用户权限菜单（菜单或者权限）
	 *
	 * @param PermissionTypeEnum $type
	 * @return array
	 * @throws ForbiddenException
	 */
	public function getTypePermissions(PermissionTypeEnum $type): array
	{
		$hasTopLevelRole = $this->hasTopLevelRole();

		// 用户权限
		if (!$hasTopLevelRole) {
			return $this->allPermissions($type);
		}

		// 所有权限
		return $this->cacheResult($type);
	}

	/**
	 * 树状结构数据
	 *
	 * @param PermissionTypeEnum|null $type
	 * @param bool                    $isCache
	 * @return array
	 */
	public static function cacheResult(PermissionTypeEnum $type = null, bool $isCache = true): array
	{
		$typeName = $type->value ?? 'all';
		$cachePrefix = self::$cachePrefix;
		$cacheKey = "{$cachePrefix}:{$typeName}";
		// 是否读取缓存
		if (!$isCache || !$data = Cache::get($cacheKey)) {
			$result = Permission::query()->where('is_show', BoolIntEnum::True->value);

			$isCache && $result->selectRaw('id, parent_id, type, name, path, component, icon, uri, method, `rank`');

			if ($type) {
				$result = $result->where('type', $type->value);
			}

			$data = $result->orderByRaw('`rank` desc, id asc')
				->get()
				->append('meta')
				->toTree()
				->toArray();
			$isCache && Cache::set($cacheKey, $data);
		}

		return $data;
	}

	/**
	 * 清除缓存
	 *
	 * @param PermissionTypeEnum|null $type
	 * @return bool
	 */
	public static function cacheClear(PermissionTypeEnum $type = null): bool
	{
		$cachePrefix = self::$cachePrefix;
		$cacheKeys = [];
		if (empty($type)) {
			$cacheKeys = [
				"{$cachePrefix}:all",
				"{$cachePrefix}:" . PermissionTypeEnum::Menu->value,
				"{$cachePrefix}:" . PermissionTypeEnum::Permission->value,
			];
		} else {
			$cacheKeys[] = "{$cachePrefix}:{$type->value}";
		}

		foreach ($cacheKeys as $cacheKey) {
			Cache::delete($cacheKey);
		}
		return true;
	}

	/**
	 * 获取所有权限
	 *
	 * @param PermissionTypeEnum|null $type
	 * @return array[]
	 */
	public function allPermissions(PermissionTypeEnum $type = null): array
	{
		$admin = $this->admin->load([
			'roles.permissions' => function ($query) use ($type) {
				$query->as('permission')
					->selectRaw('
						permission.id, permission.parent_id, permission.type, permission.name, permission.path, permission.component,
						 permission.uri, permission.method, permission.icon, permission.`rank`
					');
			}
		]);

		foreach ($admin->roles as &$role) {
			foreach ($role->permissions as &$permission) {
				unset($permission->permission);
				$permission->append('meta');
			}
		}
		$adminData = $admin->toArray();

		$adminPermissions = array_column($adminData['roles'], 'permissions');
		$adminPermissions = array_merge(...$adminPermissions);
		$menus = [];
		$permissions = [];
		foreach ($adminPermissions as $adminPermission) {
			if ((int) $adminPermission['type'] === PermissionTypeEnum::Menu->value) {
				$menus[$adminPermission['id']] = $adminPermission;
			} else {
				$permissions[$adminPermission['id']] = $adminPermission;
			}
		}

		if ($type && $type->value === PermissionTypeEnum::Menu->value) {
			return array_values($menus);
		}

		if ($type && $type->value === PermissionTypeEnum::Permission->value) {
			return array_values($permissions);
		}

		return [
			'menus'	=> array_values($menus),
			'permissions' => array_values($permissions)
		];
	}
}