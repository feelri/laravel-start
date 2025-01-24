<?php

namespace App\Services\Cloud\Huawei;

use App\Enums\Model\ConfigKeyEnum;
use App\Services\Model\ConfigService;

class Huawei
{
	/**
	 * 配置信息
	 * @var array|mixed
	 */
	public array $config;

	/**
	 * constructor
	 */
	public function __construct()
	{
		$this->config = ConfigService::static()
			->key(ConfigKeyEnum::HuaweiCloud)
			->get();
	}
}
