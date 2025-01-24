<?php

namespace App\Http\AdminApi\Controllers;

use App\Enums\Model\ConfigKeyEnum;
use App\Models\Config;
use App\Services\Model\ConfigService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * 配置
 */
class ConfigController extends Controller
{
	/**
	 * 详情
	 *
	 * @param string $key
	 * @return JsonResponse
	 */
	public function detail(string $key): JsonResponse
	{
		$config = ConfigService::static()->key(ConfigKeyEnum::tryFrom($key))->get();
		return $this->response($config);
	}

	/**
	 * 保存
	 *
	 * @param Request $request
	 * @param string  $key
	 * @return JsonResponse
	 */
	public function save(Request $request, string $key): JsonResponse
	{
		$params = $request->only(['key', 'value']);
		ConfigService::static()->key(ConfigKeyEnum::tryFrom($key))->set($params['key'] ?? null, $params['value']);
		return $this->success();
	}

	/**
	 * 云服务配置列表
	 * @return JsonResponse
	 */
	public function cloud(): JsonResponse
	{
		$keys = [
			ConfigKeyEnum::AlibabaCloud->value,
			ConfigKeyEnum::BytedanceCloud->value,
			ConfigKeyEnum::HuaweiCloud->value,
			ConfigKeyEnum::QiniuCloud->value,
			ConfigKeyEnum::TencentCloud->value,
		];
		$configs = Config::query()->whereIn('key', $keys)->get();
		return $this->response($configs);
	}
}