<?php

namespace App\Http\AdminApi\Controllers;

use App\Enums\PaginateEnum;
use App\Models\SmsLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * 短信日志
 */
class SmsLogController extends Controller
{
	/**
	 * 列表
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function __invoke(Request $request): JsonResponse
	{
		$params = $request->only(['limit', 'mobile', 'driver']);

		$result = SmsLog::query();

		if (!empty($params['mobile'])) {
			$result->where('mobile', 'like', "%{$params['mobile']}%");
		}

		if (!empty($params['driver'])) {
			$result->where('driver', $params['driver']);
		}

		$result = $result->paginate($params['limit'] ?? PaginateEnum::Default->value);
		return $this->response($result);
	}
}