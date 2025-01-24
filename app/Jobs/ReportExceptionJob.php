<?php

namespace App\Jobs;

use App\Enums\Model\ConfigKeyEnum;
use App\Enums\QueueEnum;
use App\Services\Model\ConfigService;
use App\Services\Notice\NoticeService;
use App\Services\ToolService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class ReportExceptionJob implements ShouldQueue
{
	use Queueable;

	public Request $request;
	public Throwable $exception;

	/**
	 * Create a new job instance.
	 */
	public function __construct(Request $request, Throwable $exception)
	{
		$this->request = $request;
		$this->exception = $exception;
	}

	/**
	 * 报告异常
	 *
	 * @param Request   $request
	 * @param Throwable $exception
	 * @return void
	 */
	public static function report(Request $request, Throwable $exception): void
	{
		self::dispatch($request, $exception)->onQueue(QueueEnum::ReportException->value);
	}

	/**
	 * Execute the job.
	 */
	public function handle(): void
	{
		$request = $this->request;
		$exception = $this->exception;

		ToolService::static()->ignoreException(function () use ($request, $exception) {
			// 自定义日志内容
			$paramText = json_encode($request->all(), JSON_UNESCAPED_UNICODE);
			$errorMessage = $exception->getMessage(). "\n" .
				"【".__('exception.class')."】" . $exception::class . "\n" .
				"【".__('exception.file')."】{$exception->getFile()}:{$exception->getLine()}\n" .
				"【".__('exception.request_param')."】{$paramText}\n" .
				"【".__('exception.error_stack')."】\n{$exception->getTraceAsString()} \n";
			Log::error($errorMessage);

			// webHook 通知
			if (
				!config('app.debug') &&
				ConfigService::static()->key(ConfigKeyEnum::Notice)->get('exception_notice')
			) {
				ToolService::static()->ignoreException(function () use ($errorMessage) {
					NoticeService::static()->notify($errorMessage);
				});
			}
		});
	}
}
