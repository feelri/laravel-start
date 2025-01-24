<?php

namespace App\Services\Sms;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
use App\Enums\Model\SmsDriverEnum;
use App\Enums\Model\SmsStatusEnum;
use App\Models\SmsLog;
use \Exception;

class Alibaba extends \App\Services\Cloud\Alibaba\Alibaba implements SmsInterface
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
	 * @throws ClientException
	 * @throws ServerException
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
	 * @throws ClientException
	 * @throws ServerException
	 */
	public function send($mobile, $params): array
	{
		$response = AlibabaCloud::dysmsapi()
			->v20170525()
			->sendSms()
			->withPhoneNumbers($mobile)
			->withSignName($this->sign)
			->withTemplateCode($this->code)
			->withTemplateParam(json_encode(
				$params,
				JSON_UNESCAPED_UNICODE
			))
			->request();

		// 记录请求日志
		$this->record([
			'mobile'   => "+86{$mobile}",
			'driver'   => SmsDriverEnum::Alibaba->value,
			'request'  => $response->getRequest()->data,
			'response' => $response->toArray(),
			'status'   => SmsStatusEnum::Succeed->value,
		]);

		return $response->toArray();
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
