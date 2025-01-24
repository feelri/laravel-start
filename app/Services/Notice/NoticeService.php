<?php

namespace App\Services\Notice;

use App\Enums\Model\ConfigKeyEnum;
use App\Enums\Model\NotifyDriverEnum;
use App\Services\Model\ConfigService;
use App\Traits\StaticTrait;
use \Exception;

/**
 * 通知服务
 */
class NoticeService implements NoticeInterface
{
	use StaticTrait;

	/**
	 * 驱动
	 * @var mixed
	 */
	public mixed $driver;

	/**
	 * constructor
	 * @throws Exception
	 */
	public function __construct()
	{
		$driver = ConfigService::static()->key(ConfigKeyEnum::Notice)->get('driver');
		if (empty($driver)) {
			throw new Exception(__('messages.driver_not_set', ['driver'=>__('messages.notify')]));

		}
		$driver = NotifyDriverEnum::tryFrom($driver)->driverClass();
		$this->driver = new $driver;
	}

	/**
	 * 通知
	 *
	 * @param mixed $params
	 * @return array
	 */
	public function notify(mixed $params): array
	{
		if (is_string($params)) {
			$params = ['content' => $params];
		}
		return $this->driver->notify($params);
	}
}
