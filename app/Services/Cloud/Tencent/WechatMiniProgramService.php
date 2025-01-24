<?php

namespace App\Services\Cloud\Tencent;

use App\Enums\Model\ConfigKeyEnum;
use App\Services\Model\ConfigService;
use EasyWeChat\Kernel\Exceptions\BadResponseException;
use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;
use EasyWeChat\Kernel\HttpClient\AccessTokenAwareClient;
use EasyWeChat\MiniApp\Application;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class WechatMiniProgramService
{
	/**
	 * 配置信息
	 * @var array|mixed
	 */
	public array $config;

	/**
	 * 小程序实例
	 * @var Application
	 */
	public Application $app;

	/**
	 * constructor
	 *
	 * @param array $config
	 * @throws InvalidArgumentException
	 */
	public function __construct(array $config = [])
	{
		$this->config = ConfigService::static()
			->key(ConfigKeyEnum::TencentCloud)
			->get('wechat.miniProgram');

		$this->app = new Application($this->config);
	}

	/**
	 * 客户端
	 * @return AccessTokenAwareClient
	 */
	public function client(): AccessTokenAwareClient
	{
		return $this->app->getClient();
	}

	/**
	 * 授权登录
	 *
	 * @param string $code
	 * @return array
	 * @throws BadResponseException
	 * @throws ClientExceptionInterface
	 * @throws DecodingExceptionInterface
	 * @throws RedirectionExceptionInterface
	 * @throws ServerExceptionInterface
	 * @throws TransportExceptionInterface
	 */
	public function code2Session(string $code): array
	{
		$response = $this->client()->postJson('/sns/jscode2session', [
			'code'	=> $code
		]);
		return $response->toArray();
	}
}
