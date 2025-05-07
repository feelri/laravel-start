<?php

namespace App\Jobs;

use App\Enums\Model\ConfigKeyEnum;
use App\Enums\QueueEnum;
use App\Services\ExceptionRecordService;
use App\Services\Model\ConfigService;
use App\Services\Notice\NoticeService;
use App\Services\ToolService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * 系统异常日志队列
 */
class ReportExceptionJob implements ShouldQueue
{
	use Queueable;

	public string $errorMessage;

	/**
	 * Create a new job instance.
	 */
	public function __construct(string $errorMessage)
	{
		$this->errorMessage = $errorMessage;
	}

	/**
	 * 报告异常
	 *
	 * @param string $errorMessage
	 * @return void
	 */
	public static function report(string $errorMessage): void
	{
		self::dispatch($errorMessage)->onQueue(QueueEnum::ReportException->value);
	}

	/**
	 * Execute the job.
	 */
	public function handle(): void
	{
		ExceptionRecordService::static()->record($this->errorMessage);
	}
}
