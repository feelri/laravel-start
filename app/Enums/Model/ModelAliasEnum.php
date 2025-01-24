<?php

namespace App\Enums\Model;

use App\Enums\CollectTrait;
use App\Models\Admin\Admin;
use App\Models\User\User;

/**
 * 模型别名
 */
enum ModelAliasEnum: string
{
	use CollectTrait;

	case Admin = Admin::class;
	case User = User::class;
}