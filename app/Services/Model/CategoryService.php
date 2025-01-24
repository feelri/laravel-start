<?php

namespace App\Services\Model;

use App\Enums\Model\CategoryTypeEnum;
use App\Enums\Model\PermissionTypeEnum;
use App\Models\Category;
use App\Services\ToolService;
use Illuminate\Support\Facades\Cache;

/**
 * 权限菜单服务
 */
class CategoryService
{
	public static string $cachePrefix = 'categories';

	/**
	 * 树状结构数据（缓存）
	 *
	 * @param CategoryTypeEnum $type
	 * @return array
	 */
	public static function cacheResult(CategoryTypeEnum $type): array
	{
		$typeName = $type->value ?? 'all';
		$cachePrefix = self::$cachePrefix;
		$cacheKey = "{$cachePrefix}:{$typeName}";

		$data = Cache::get($cacheKey);
		if (empty($data)) {
			$result = Category::query();
			if ($type) {
				$result = $result->where('type', $type->value);
			}
			$data = $result->selectRaw('id, parent_id, type, name, icon, `rank`')
				->orderByRaw('`rank` desc, id asc')
				->get()
				->toArray();
			if (!empty($data)) {
				$data = ToolService::static()->tree($data, 'id', 'parent_id');
			}
			Cache::set($cacheKey, $data);
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
}