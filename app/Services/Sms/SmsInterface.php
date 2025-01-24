<?php

namespace App\Services\Sms;

use App\Models\SmsLog;

interface SmsInterface
{
	/**
	 * 通过手机号发送
	 *
	 * @param string $mobile
	 * @param array  $params
	 * @return array
	 */
	public function sendByMobile(string $mobile, array $params): array;

	/**
	 * 发送短信
	 *
	 * @param string $mobile
	 * @param array  $params
	 * @return array
	 */
	public function send(string $mobile, array $params): array;

	/**
	 * 记录日志
	 *
	 * @param array $data
	 * @return SmsLog
	 */
	public function record(array $data): SmsLog;
}
