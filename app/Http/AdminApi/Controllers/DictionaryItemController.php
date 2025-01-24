<?php

namespace App\Http\AdminApi\Controllers;

use App\Enums\PaginateEnum;
use App\Models\Dictionary\DictionaryItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * 字典明细
 */
class DictionaryItemController extends Controller
{
	/**
	 * 列表
	 *
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function index(Request $request): JsonResponse
	{
		$params = $request->only(['limit', 'dictionary_id', 'dictionary_key', 'parent_id', 'keyword']);
		$dictionaries = DictionaryItem::query();

		if (!empty($params['type'])) {
			$dictionaries->where('type', $params['type']);
		}

		if (!empty($params['parent_id'])) {
			$dictionaries->where('parent_id', $params['parent_id']);
		}

		if (!empty($params['dictionary_id'])) {
			$dictionaries->where('dictionary_id', $params['dictionary_id']);
		}

		if (!empty($params['dictionary_key'])) {
			$dictionaries->whereHasIn('dictionary', function ($query) use ($params) {
				$query->where('key', $params['dictionary_key']);
			});
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
		$params = $request->only(['dictionary_id', 'parent_id', 'key', 'value', 'label']);
		$dictionaryItem = DictionaryItem::query()->create($params);
		return $this->response(['id'=>$dictionaryItem]);
	}

	/**
	 * 详情
	 *
	 * @param DictionaryItem $dictionaryItem
	 * @return JsonResponse
	 */
	public function show(DictionaryItem $dictionaryItem): JsonResponse
	{
		return $this->response($dictionaryItem);
	}

	/**
	 * 保存
	 *
	 * @param Request $request
	 * @param DictionaryItem     $dictionaryItem
	 * @return JsonResponse
	 */
	public function update(Request $request, DictionaryItem $dictionaryItem): JsonResponse
	{
		$params = $request->only(['dictionary_id', 'parent_id', 'key', 'value', 'label']);
		$dictionaryItem->fill($params)->save();
		return $this->success(__("response.success_save"));
	}

	/**
	 * 删除
	 *
	 * @param DictionaryItem $dictionaryItem
	 * @return Response
	 */
	public function destroy(DictionaryItem $dictionaryItem): Response
	{
		$dictionaryItem->delete();
		return $this->noContent();
	}
}