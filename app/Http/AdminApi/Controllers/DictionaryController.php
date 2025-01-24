<?php

namespace App\Http\AdminApi\Controllers;

use App\Enums\PaginateEnum;
use App\Models\Dictionary\Dictionary;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

/**
 * 字典
 */
class DictionaryController extends Controller
{
	/**
	 * 列表
	 *
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function index(Request $request): JsonResponse
	{
		$params = $request->only(['limit', 'type', 'parent_id', 'keyword']);
		$dictionaries = Dictionary::query()->where('type', $params['type'] ?? '');

		if (!empty($params['type'])) {
			$dictionaries->where('type', $params['type']);
		}

		if (!empty($params['parent_id'])) {
			$dictionaries->where('parent_id', $params['parent_id']);
		}

		if (!empty($params['keyword'])) {
			$dictionaries->where(function ($query) use ($params) {
				$query->where('label', 'like', "%{$params['keyword']}%")
					->orWhere('key', 'like', "%{$params['keyword']}%")
					->orWhere('value', 'like', "%{$params['keyword']}%");
			});
		}

		$result = $dictionaries->paginate($params['limit'] ?? PaginateEnum::Default->value);
		return $this->response($result);
	}

	/**
	 * 创建
	 *
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function store(Request $request): JsonResponse
	{
		$params = $request->only(['category_id', 'key', 'name', 'description']);
		$dictionary = Dictionary::query()->create($params);
		return $this->response(['id'=>$dictionary]);
	}

	/**
	 * 详情
	 *
	 * @param Dictionary $dictionary
	 * @return JsonResponse
	 */
	public function show(Dictionary $dictionary): JsonResponse
	{
		$dictionary->load(['items']);
		return $this->response($dictionary);
	}

	/**
	 * 保存
	 *
	 * @param Request $request
	 * @param Dictionary     $dictionary
	 * @return JsonResponse
	 */
	public function update(Request $request, Dictionary $dictionary): JsonResponse
	{
		$params = $request->only(['category_id', 'key', 'name', 'description']);
		$dictionary->fill($params)->save();
		return $this->success(__("response.success_save"));
	}

	/**
	 * 删除
	 *
	 * @param Dictionary $dictionary
	 * @return Response
	 */
	public function destroy(Dictionary $dictionary): Response
	{
		DB::transaction(function () use ($dictionary) {
			$dictionary->items()->delete();
			$dictionary->delete();
		});
		return $this->noContent();
	}
}