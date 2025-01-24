<?php

use App\Http\AdminApi\Middleware\PermissionMiddleware;
use App\Http\Api\Middleware\AuthenticateMiddleware;
use App\Http\Api\Middleware\ThrottleWithRedisMiddleware;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance;
use Illuminate\Foundation\Http\Middleware\TrimStrings;
use Illuminate\Http\Middleware\HandleCors;
use Illuminate\Http\Middleware\TrustHosts;
use Illuminate\Http\Middleware\TrustProxies;
use Illuminate\Http\Middleware\ValidatePostSize;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;

/**
 * 全局中间件
 * @param Middleware $middleware
 */
return function (Middleware $middleware) {
	$middleware
		/**
		 * 默认中间件
		 */
		->use([
			TrustHosts::class,
			TrustProxies::class,
			HandleCors::class,
			PreventRequestsDuringMaintenance::class,
			ValidatePostSize::class,
			TrimStrings::class,
			//			ConvertEmptyStringsToNull::class,
		])
		/**
		 * 中间件别名
		 */
		->alias([
			'auth'             => AuthenticateMiddleware::class,
			'permission.admin' => PermissionMiddleware::class,
			'limiter'          => ThrottleWithRedisMiddleware::class,
			'abilities'        => CheckAbilities::class,
		])

		/**
		 * 自定义中间件
		 */
//		->append()
	;
};
