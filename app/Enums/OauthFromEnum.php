<?php

namespace App\Enums;

use App\Enums\CollectTrait;

/**
 * oauth 授权应用来源
 */
enum OauthFromEnum: string
{
	use CollectTrait;

	case WechatMiniProgram = 'wechat_mini_program';
}
