<?php

namespace App\Enums\Model;

use App\Enums\CollectTrait;

/**
 * 权限菜单类型
 */
enum PermissionTypeEnum: int
{
	use CollectTrait;

	case Menu = 1; // 菜单
	case Permission = 2; // 权限

	/**
	 * 枚举文本转换
	 * @return string
	 */
	public function label(): string
	{
		return match ($this) {
			self::Menu => __('enum.permission.menu'),
			self::Permission => __('enum.permission.permission'),
		};
	}
}
