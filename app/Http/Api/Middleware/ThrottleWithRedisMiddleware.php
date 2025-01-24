<?php

namespace App\Http\Api\Middleware;

use App\Enums\Model\ConfigKeyEnum;
use App\Services\Model\ConfigService;
use \Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Exceptions\MissingRateLimiterException;
use Illuminate\Routing\Middleware\ThrottleRequestsWithRedis;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

class ThrottleWithRedisMiddleware extends ThrottleRequestsWithRedis
{
	/**
	 * 重新父级方法
	 *
	 * @param Request $request
	 * @param Closure $next
	 * @param int|string $maxAttempts
	 * @param float|int $decayMinutes
	 * @param string $prefix
	 * @return Response
	 * @throws MissingRateLimiterException
	 */
	public function handle($request, Closure $next, $maxAttempts = 60, $decayMinutes = 1, $prefix = ''): Response
	{
		// 获取系统配置
		$service      = ConfigService::static()->key(ConfigKeyEnum::System);
		$maxAttempts  = $service->get('limiter.maxAttempts');
		$decayMinutes = $service->key(ConfigKeyEnum::System)->get('limiter.decayMinutes');

		return parent::handle($request, $next, $maxAttempts, $decayMinutes, $prefix); // TODO: Change the autogenerated stub
	}

	/**
	 * Resolve request signature.
	 *
	 * @param Request $request
	 * @return string
	 *
	 * @throws RuntimeException
	 */
	protected function resolveRequestSignature($request): string
	{
		if ($user = $request->user()) {
			return $this->formatIdentifier($user->getAuthIdentifier() . '|' . $request->ip());
		} elseif ($route = $request->route()) {
			return $this->formatIdentifier($route->getDomain() . '|' . $request->ip());
		}

		throw new RuntimeException('Unable to generate the request signature. Route unavailable.');
	}

	/**
	 * Format the given identifier based on the configured hashing settings.
	 *
	 * @param string $value
	 * @return string
	 */
	private function formatIdentifier(string $value): string
	{
		return self::$shouldHashKeys ? sha1($value) : $value;
	}
}