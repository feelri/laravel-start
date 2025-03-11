<?php

namespace App\Enums;

/**
 * api 接口 code（非 HTTP status）
 */
enum HTTPCodeEnum: int
{
	/**
	 * 成功
	 */
	case Success = 0;

	/**
	 * 错误
	 */
	case Error = 10000; // 默认
	case ErrorArticleViolation = 10100; // 文章违规

	case ErrorAuth = 20000; // 授权类错误
	case ErrorAuthExpiredToken = 20001; // token 错误（过期或者错误）
	case ErrorAuthRefreshToken = 20002; // refresh_token 错误（过期或者错误）
	case ErrorAuthBasic = 20010; // 基本信息设置有误
	case ErrorAuthStatus = 20011; // 账号异常
	case ErrorAuthMobile = 20012; // 未绑定手机号

	case ErrorAuthCurrentShopId = 20100; // 没有未选择门店

	case ErrorAccountAbnormal = 30000; // 账户类错误

	case ErrorPermission = 40000; // 权限类错误

	case ErrorParameter = 50000; // 参数类错误
}
