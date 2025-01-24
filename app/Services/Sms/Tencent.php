<?php

namespace App\Services\Sms;

use App\Enums\Model\SmsDriverEnum;
use App\Enums\Model\SmsStatusEnum;
use App\Exceptions\ErrorException;
use App\Models\SmsLog;
use \Exception;
use TencentCloud\Common\Credential;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Sms\V20210111\Models\SendSmsRequest;
use TencentCloud\Sms\V20210111\SmsClient;

class Tencent extends \App\Services\Cloud\Tencent\Tencent implements SmsInterface
{
	/**
	 * 短信签名
	 * @var string
	 */
	public string $sign = '123';

	/**
	 * 短信模板 code
	 * @var string
	 */
	public string $code = '132';

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
	 * @param string $mobile
	 * @param array  $params
	 * @return array
	 * @throws ErrorException
	 */
	public function send(string $mobile, array $params): array
	{
		$cred        = new Credential($this->config['secretId'], $this->config['secretKey']);
		$httpProfile = new HttpProfile();
		$httpProfile->setEndpoint("sms.tencentcloudapi.com");

		// 实例化一个client选项，可选的，没有特殊需求可以跳过
		$clientProfile = new ClientProfile();
		$clientProfile->setHttpProfile($httpProfile);
		$client = new SmsClient($cred, $this->config['region'], $clientProfile);

		// 实例化一个请求对象,每个接口都会对应一个request对象
		$request = new SendSmsRequest();

		$params = [
			"PhoneNumberSet"   => [$mobile],
			"SmsSdkAppId"      => $this->config['sdkAppId'],
			"TemplateId"       => $this->code,
			"SignName"         => $this->sign,
			"TemplateParamSet" => $params
		];
		$request->fromJsonString(json_encode($params));

		// 返回的response是一个SendSmsResponse的实例，与请求对象对应
		$response = $client->SendSms($request);
		$data     = json_decode($response->toJsonString(), true);

		// 记录请求日志
		$this->record([
			'mobile'   => "+86{$mobile}",
			'driver'   => SmsDriverEnum::Tencent->value,
			'request'  => $params,
			'response' => $data,
			'status'   => SmsStatusEnum::Succeed->value,
		]);

		if ($data['SendStatusSet'][0]['Message']) {
			throw new ErrorException($data['SendStatusSet'][0]['Message']);
		}

		return $data;
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
