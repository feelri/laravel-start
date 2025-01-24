<?php

namespace App\Services\Sms;

use App\Enums\Model\ConfigKeyEnum;
use App\Enums\Model\SmsDriverEnum;
use App\Services\Model\ConfigService;
use \Exception;

class SmsService
{
	/**
	 * 驱动
	 * @var mixed
	 */
	public mixed $driver;

	/**
	 * constructor
	 *
	 * @param string $sign
	 * @param string $code
	 * @throws Exception
	 */
	public function __construct(string $sign = '', string $code = '')
	{
		$driver = ConfigService::static()->key(ConfigKeyEnum::Sms)->get('driver');
		if (empty($driver)) {
			throw new Exception(__('messages.driver_not_set', ['driver'=>__('messages.sms')]));
		}
		$driver = SmsDriverEnum::tryFrom($driver)->driverClass();
		$driver = (new $driver)->sign($sign)->code($code);
		$this->driver = $driver;
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