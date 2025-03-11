<?php

namespace App\Http\Api\Middleware;

use App\Services\ToolService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class LocalizationMiddleware
{
    /**
     * Handle an incoming request.
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
		$language = $request->input('language');
		if (empty($language)) {
			$acceptLanguage = $request->header('accept-language', 'zh-CN');
			$language = ToolService::static()->getPreferredLanguage($queryLanguage ?? $acceptLanguage);
		}
		App::setLocale($language ?? 'zh-CN');
		return $next($request);
	}
}
