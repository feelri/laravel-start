<?php

namespace App\Enums\Model;

use App\Enums\CollectTrait;

/**
 * oauth 授权应用来源
 */
enum UserOauthFromEnum: string
{
	use CollectTrait;

	case WechatMiniProgram = 'wechat_mini_program';
}