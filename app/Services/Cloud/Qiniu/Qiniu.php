<?php

namespace App\Services\Cloud\Qiniu;

use App\Enums\Model\ConfigKeyEnum;
use App\Services\Model\ConfigService;

class Qiniu
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
			->key(ConfigKeyEnum::QiniuCloud)
			->get();
	}
}
