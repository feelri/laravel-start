<?php

namespace App\Services\Sms;

use App\Enums\Model\SmsDriverEnum;
use App\Enums\Model\SmsStatusEnum;
use App\Exceptions\ErrorException;
use App\Models\SmsLog;
use \Exception;

class Huawei extends \App\Services\Cloud\Huawei\Huawei implements SmsInterface
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
		$signer = new HuaweiSigner();
		// 认证用的appKey和appSecret硬编码到代码中或者明文存储都有很大的安全风险，建议在配置文件或者环境变量中密文存放，使用时解密，确保安全；
		$signer->Key    = $this->config['accessKey'];      // app key
		$signer->Secret = $this->config['secretKey'];      // app secret

		$request = new HuaweiRequest('POST', 'https://smsapi.cn-north-4.myhuaweicloud.com:443/sms/batchSendSms/v1');
		//Add header parameters
		$request->headers = [
			'content-type' => 'application/x-www-form-urlencoded',
		];

		$body          = [
			'from'          => $this->sign,
			'to'            => "+86{$mobile}",
			'templateId'    => $this->code,
			'templateParas' => json_encode($params),
			//'statusCallback'	=> 'https://your.server.com/rest/callback/statusReport',
		];
		$request->body = http_build_query($body);
		$curl          = $signer->Sign($request);

		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		$response = curl_exec($curl);
		$data     = json_decode($response, true);
		$status   = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);

		// 记录请求日志
		$this->record([
			'mobile'   => $mobile,
			'driver'   => SmsDriverEnum::Huawei->value,
			'request'  => (array)$request,
			'response' => $data,
			'status'   => $status !== 200 ? SmsStatusEnum::Failed->value : SmsStatusEnum::Succeed->value
		]);

		if ($status !== 200) {
			throw new ErrorException($data['description']);
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
