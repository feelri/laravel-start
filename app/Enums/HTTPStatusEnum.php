<?php

namespace App\Enums;

/**
 * HTTP 状态码
 */
enum HTTPStatusEnum: int
{
	use CollectTrait;

	/**
	 * 请求成功
	 */
	case Ok       = 200; // 成功
	case Created  = 201; // 创建了新的资源
	case Accepted = 202; // 尚未进行处理

	/**
	 * 客户端错误
	 */
	case Bad              = 400;
	case Unauthorized     = 401; // 未授权
	case Payment          = 402; // 未付款
	case Forbidden        = 403; // 拒绝授权访问
	case NotFound         = 404; // 资源不存在
	case MethodNotAllowed = 405; // 请求方式不允许
	case ParamBad         = 422; // 参数错误

	/**
	 * 服务端错误
	 */
	case Error       = 500; // 服务端错误
	case Unavailable = 503; // 服务不可用

	/**
	 * 枚举文本转换
	 * @return string
	 */
	public function label(): string
	{
		return match ($this) {
			self::Ok               => __('enum.http.ok'),
			self::Created          => __('enum.http.created'),
			self::Accepted         => __('enum.http.accepted'),
			self::Bad              => __('enum.http.bad'),
			self::Unauthorized     => __('enum.http.unauthorized'),
			self::Payment          => __('enum.http.payment'),
			self::Forbidden        => __('enum.http.forbidden'),
			self::ParamBad         => __('enum.http.paramBad'),
			self::Error            => __('enum.http.error'),
			self::NotFound         => __('enum.http.not_found'),
			self::MethodNotAllowed => __('enum.http.method_not_allowed'),
			self::Unavailable      => __('enum.http.unavailable'),
		};
	}
}
