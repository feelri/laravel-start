<?php

namespace App\Services\Cloud\Bytedance;

use App\Enums\Model\ConfigKeyEnum;
use App\Services\Model\ConfigService;
use GuzzleHttp\Client;

/**
 * 钉钉
 */
class Feishu
{
	/**
	 * HTTP 客户端
	 * @var Client
	 */
	public Client $client;

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
			->key(ConfigKeyEnum::BytedanceCloud)
			->get('feishu');
		$this->client = new Client([
			'headers'	=> [
				'Content-Type'=> 'application/json'
			]
		]);
	}

	/**
	 * 签名
	 *
	 * @param $timestamp
	 * @param $secret
	 * @return string
	 */
	public function signature($timestamp, $secret): string
	{
		$string = $timestamp . "\n" . $secret;
		return base64_encode(hash_hmac('sha256', '', $string, true));
	}
}