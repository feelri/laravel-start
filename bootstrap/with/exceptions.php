<?php

use App\Enums\HTTPCodeEnum;
use App\Enums\HTTPStatusEnum;
use App\Exceptions\AuthException;
use App\Exceptions\BaseException;
use App\Exceptions\ForbiddenException;
use App\Exceptions\ParameterException;
use App\Exceptions\ResourceException;
use App\Jobs\ReportExceptionJob;
use App\Services\ResponseService;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

$routeConfigs = include __DIR__ . '/other-route-config.php';

/**
 * 异常处理
 * @param Exceptions $exceptions
 */
return function (Exceptions $exceptions) use ($routeConfigs) {
    // 无需报告的异常
    $exceptions->dontReport([
        AuthException::class,
        ForbiddenException::class,
        ParameterException::class,
        ResourceException::class
    ]);

    // 报告异常
    $exceptions->report(function (Throwable $e) {
		ReportExceptionJob::report(Request::capture(), $e);
    })->stop();

    // 渲染异常
    $exceptions->render(function (Throwable $e, Request $request) use ($routeConfigs) {
        if ($request->is('api', 'api/*', ...$routeConfigs['patterns'])) {
			if ($e instanceof NotFoundHttpException) {
				throw new ResourceException();
			}

			if ($e instanceof BaseException) {
				// HTTP 异常
				$httpCode = -1;
				$code = HTTPCodeEnum::Error;
				if ($e instanceof HttpExceptionInterface) {
					$httpCode = $e->getStatusCode();
					$code = HTTPCodeEnum::tryFrom($e->getCode());
				}

				// 默认验证器异常
				if ($e instanceof ValidationException) {
					$httpCode = $e->status;
				}

				$status = HTTPStatusEnum::tryFrom($httpCode) ?? HTTPStatusEnum::Unavailable;
				return (new ResponseService())->response(
					message: $e->getMessage() ?? $status->label(),
					status: $status,
					code: $code
				);
			}
        }
    });
};
