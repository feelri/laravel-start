<?php

namespace App\Http\AdminApi\Controllers;

use App\Enums\Model\CategoryTypeEnum;
use App\Enums\PaginateEnum;
use App\Exceptions\ForbiddenException;
use App\Http\AdminApi\Requests\Category\SaveRequest;
use App\Http\AdminApi\Requests\Category\TreeRequest;
use App\Models\Category;
use App\Services\Model\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * 分类
 */
class CategoryController extends Controller
{
	/**
	 * 列表
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function index(Request $request): JsonResponse
	{
		$params     = $request->only(['type', 'keyword', 'limit']);
		$type       = empty($params['type']) ? CategoryTypeEnum::FileUpload->value : $params['type'];
		$categories = Category::query()->where('type', $type);
		if (!empty($params['keyword'])) {
			$categories->where(function ($query) use ($params) {
				$query->where('name', 'like', "%{$params['keyword']}%");
			});
		}
		$categories = $categories->paginate($params['limit'] ?? PaginateEnum::Default->value);
		return $this->response($categories);
	}

	/**
	 * 新增
	 *
	 * @param SaveRequest $request
	 * @return JsonResponse
	 */
	public function store(SaveRequest $request): JsonResponse
	{
		$params   = $request->only(['type', 'parent_id', 'name', 'icon', 'rank']);
		$category = Category::query()->create($params);
		CategoryService::cacheClear(CategoryTypeEnum::tryFrom($params['type']));
		return $this->response(['id' => $category->id]);
	}

	/**
	 * 详情
	 *
	 * @param Category $category
	 * @return JsonResponse
	 */
	public function show(Category $category): JsonResponse
	{
		return $this->response($category);
	}

	/**
	 * 修改
	 *
	 * @param SaveRequest $request
	 * @param Category    $category
	 * @return JsonResponse
	 */
	public function update(SaveRequest $request, Category $category): JsonResponse
	{
		$params              = $request->only(['type', 'parent_id', 'name', 'icon', 'rank']);
		$category->type      = $params['type'] ?? CategoryTypeEnum::FileUpload->value;
		$category->parent_id = $params['parent_id'] ?? 0;
		$category->name      = $params['name'] ?? '';
		$category->icon      = $params['icon'] ?? '';
		$category->rank      = $params['icon'] ?? 0;
		$category->save();
		CategoryService::cacheClear(CategoryTypeEnum::tryFrom($params['type']));
		return $this->success();
	}

	/**
	 * 删除
	 *
	 * @param Category $category
	 * @return JsonResponse
	 * @throws ForbiddenException
	 */
	public function destroy(Category $category): JsonResponse
	{
		// 获取是否存在子级
		$child = Category::query()->where('parent_id', $category->id)->first();
		if ($child) {
			throw new ForbiddenException(__('messages.has_child'));
		}
		$category->delete();
		CategoryService::cacheClear(CategoryTypeEnum::tryFrom($category->type));
		return $this->success();
	}

	/**
	 * 树状结构数据
	 *
	 * @param TreeRequest $request
	 * @return JsonResponse
	 */
	public function tree(TreeRequest $request): JsonResponse
	{
		$params = $request->only(['type']);
		$result = CategoryService::cacheResult(CategoryTypeEnum::tryFrom($params['type']));
		return $this->response($result);
	}
}
