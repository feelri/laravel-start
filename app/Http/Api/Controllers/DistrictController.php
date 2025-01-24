<?php

namespace App\Http\Api\Controllers;

use App\Models\District;
use App\Services\ToolService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 * 地区
 */
class DistrictController extends Controller
{
	/**
	 * 列表
	 *
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function index(Request $request): JsonResponse
	{
		$params = $request->only(['keyword', 'parent_id']);
		$districts = District::query();

		if (!empty($params['keyword'])) {
			$districts = $districts->where(function ($query) use ($params) {
				$query->where('name', 'like', "%{$params['keyword']}%")
					->orWhere('short_name', 'like', "%{$params['keyword']}%")
					->orWhere('merger_name', 'like', "%{$params['keyword']}%");
			});
		}

		if ($params['parent_id'] ?? false) {
			$districts = $districts->where('parent_id', $params['parent_id']);
		}

		$districts = $districts->get();

		return $this->response($districts);
	}

    /**
     * 树状结构数据
     * @return JsonResponse
     */
    public function tree(): JsonResponse
    {
		$cacheKey = 'district-tree';
        $districts = District::query()->get();

		$data = Cache::get($cacheKey);
		if (empty($data)) {
			$data = ToolService::static()->tree($districts->toArray(), 'area_code', 'parent_id');
			Cache::set($cacheKey, $data);
		}

        return $this->response($data);
    }
}
