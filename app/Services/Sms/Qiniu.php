<?php

namespace App\Services\Sms;

use App\Enums\Model\SmsDriverEnum;
use App\Enums\Model\SmsStatusEnum;
use App\Exceptions\ErrorException;
use App\Models\SmsLog;
use \Exception;
use Qiniu\Auth;
use Qiniu\Sms\Sms;

class Qiniu extends \App\Services\Cloud\Qiniu\Qiniu implements SmsInterface
{
	/**
	 * 短信签名
	 * @var string
	 */
	public string $sign = '';

	/**
	 * 短信模板 code
	 * @var string
	 */
	public string $code = '';

	/**
	 * constructor
	 * @throws Exception
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * 设置签名
	 *
	 * @param string $sign
	 * @return $this
	 */
	public function sign(string $sign): static
	{
		$this->sign = $sign;
		return $this;
	}

	/**
	 * 设置签名
	 *
	 * @param string $code
	 * @return $this
	 */
	public function code(string $code): static
	{
		$this->code = $code;
		return $this;
	}

	/**
	 * 通过手机号发送
	 *
	 * @param string $mobile
	 * @param array  $params
	 * @return array
	 * @throws ErrorException
	 */
	public function sendByMobile(string $mobile, array $params): array
	{
		return $this->send($mobile, $params);
	}

	/**
	 * 发送短信
	 *
	 * @param $mobile
	 * @param $params
	 * @return array
	 * @throws ErrorException
	 */
	public function send($mobile, $params): array
	{
		$auth = new Auth($this->config['accessKey'], $this->config['secretKey']);
		$client = new Sms($auth);
		[$response, $error] = $client->sendMessage($this->code, [$mobile], $params);
		$errorData = json_decode($error?->getResponse()->body, true);

		// 记录请求日志
		$this->record([
			'mobile'   => $mobile,
			'driver'   => SmsDriverEnum::Qiniu->value,
			'request'  => [$this->code, [$mobile], $params],
			'response' => $error ? $errorData : $response,
			'status'   => $error ? SmsStatusEnum::Failed->value : SmsStatusEnum::Succeed->value
		]);

		if ($error) {
			throw new ErrorException($errorData['message'] ?? __('messages.sms_send_abnormal'));
		}

		return $response;
	}

	/**
	 * 记录日志
	 *
	 * @param array $data
	 * @return SmsLog
	 */
	public function record(array $data): SmsLog
	{
		return SmsLog::query()->create($data);
	}
}
