<?php

namespace App\Services;

use App\Enums\Model\ConfigKeyEnum;
use App\Jobs\ReportExceptionJob;
use App\Services\Model\ConfigService;
use App\Services\Notice\NoticeService;
use App\Traits\StaticTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use \Throwable;

/**
 * 异常处理
 */
class ExceptionRecordService
{
	use StaticTrait;

	/**
	 * 报告异常
	 *
	 * @param Throwable $exception
	 * @param bool  $withRequest
	 * @return void
	 */
	public function report(Throwable $exception, bool $withRequest = true): void
	{
		$request = $withRequest ? Request::capture() : null;
		$errorMessage = $this->getHttpErrorMessage($request, $exception);

		ConfigService::static()
			->key(ConfigKeyEnum::System)
			->get('exception_queue')
		? ReportExceptionJob::report($errorMessage)
		: $this->record($errorMessage);
	}

	/**
	 * 记录异常
	 *
	 * @param string $message
	 * @return void
	 */
	public function record(string $message): void
	{
		ToolService::static()->ignoreException(function () use ($message) {
			Log::error($message);

			// webHook 通知
			if (
				!config('app.debug') &&
				ConfigService::static()->key(ConfigKeyEnum::Notice)->get('exception_notice')
			) {
				ToolService::static()->ignoreException(function () use ($message) {
					NoticeService::static()->notify($message);
				});
			}
		});
	}

	/**
	 * 错误日志
	 *
	 * @param Request|null $request
	 * @param Throwable    $exception
	 * @return string
	 */
	protected function getHttpErrorMessage(?Request $request, Throwable $exception): string
	{
		// 自定义日志内容
		$params = $request?->all() ?? null;

		$paramText = $params ? json_encode($params, JSON_UNESCAPED_UNICODE) : '无';
		return $exception->getMessage() . "\n" .
			"【" . __('exception.class') . "】" . $exception::class . "\n" .
			"【" . __('exception.file') . "】{$exception->getFile()}:{$exception->getLine()}\n" .
			"【" . __('exception.request_param') . "】{$paramText}\n" .
			"【" . __('exception.error_stack') . "】\n{$exception->getTraceAsString()} \n";
	}
}