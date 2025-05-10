<?php

namespace App\Services\Sms;
use App\Enums\Model\ConfigKeyEnum;
use App\Enums\Model\SmsDriverEnum;
use App\Services\Model\ConfigService;
use App\Traits\StaticTrait;
use Exception;

class SmsService
{
	use StaticTrait;

	/**
	 * 驱动
	 * @var mixed
	 */
	public mixed $driver;

	/**
	 * 短信签名
	 * @var mixed
	 */
	public mixed $sign;

	/**
	 * 短信模板
	 * @var mixed
	 */
	public mixed $code;

	/**
	 * constructor
	 *
	 * @param string $sign
	 * @param string $code
	 * @throws Exception
	 */
	public function __construct(string $sign = '', string $code = '')
	{
		$configService = ConfigService::static();
		$driver = $configService->key(ConfigKeyEnum::Sms)->get('driver');
		if (empty($driver)) {
			throw new Exception('未设置短信驱动');
		}

		// 获取短信配置
		$sign = $configService->key(ConfigKeyEnum::AlibabaCloud)->get('SMS.sign');
		if (empty($sign)) {
			throw new Exception('未设置短信签名');
		}

		$code = $configService->key(ConfigKeyEnum::AlibabaCloud)->get('SMS.template')[0]['code'] ?? null;
		if (empty($code)) {
			throw new Exception('未设置短信模板');
		}

		$driver = SmsDriverEnum::tryFrom($driver)->driverClass();
		$driver = (new $driver)->sign($sign)->code($code);
		$this->driver = $driver;
	}

	/**
	 * 设置短信签名
	 *
	 * @param string $sign
	 * @return $this
	 */
	public function sign(string $sign): static
	{
		$this->sign = $sign;
		$this->driver->sign($sign);
		return $this;
	}

	/**
	 * 设置短信模板
	 *
	 * @param string $code
	 * @return $this
	 */
	public function code(string $code): static
	{
		$this->code = $code;
		$this->driver->code($code);
		return $this;
	}

	/**
	 * 发送
	 *
	 * @param string $mobile
	 * @param array  $params
	 * @return array
	 */
	public function sendByMobile(string $mobile, array $params): array
	{
		return $this->driver->sendByMobile($mobile, $params);
	}
}