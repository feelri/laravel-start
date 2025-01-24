<?php

namespace App\Http\AdminApi\Controllers;

use App\Enums\PaginateEnum;
use App\Exceptions\ForbiddenException;
use App\Http\AdminApi\Requests\Permission\SaveRequest;
use App\Models\Permission\Permission;
use App\Services\Model\PermissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * 权限菜单
 */
class PermissionController extends Controller
{
	/**
	 * 列表
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function index(Request $request): JsonResponse
	{
		$params      = $request->only(['keyword', 'limit']);
		$permissions = Permission::query();
		if (!empty($params['keyword'])) {
			$permissions->where(function ($query) use ($params) {
				$query->where('name', 'like', "%{$params['keyword']}%")
					->orWhere('uri', 'like', "%{$params['keyword']}%")
					->orWhere('component', 'like', "%{$params['keyword']}%");
			});
		}
		$permissions = $permissions->paginate($params['limit'] ?? PaginateEnum::Default->value);
		return $this->response($permissions);
	}

	/**
	 * 新增
	 *
	 * @param SaveRequest $request
	 * @return JsonResponse
	 */
	public function store(SaveRequest $request): JsonResponse
	{
		$params = $request->only([
			'parent_id',
			'type',
			'name',
			'icon',
			'uri',
			'path',
			'method',
			'component',
			'is_show',
			'is_disable',
			'rank'
		]);

		$params['parent_id'] = empty($params['parent_id']) ? null : $params['parent_id'];
		$permission          = Permission::query()->create($params);
		PermissionService::cacheClear();
		return $this->response(['id' => $permission->id]);
	}

	/**
	 * 详情
	 *
	 * @param Permission $permission
	 * @return JsonResponse
	 */
	public function show(Permission $permission): JsonResponse
	{
		return $this->response($permission);
	}

	/**
	 * 修改
	 *
	 * @param SaveRequest $request
	 * @param Permission  $permission
	 * @return JsonResponse
	 */
	public function update(SaveRequest $request, Permission $permission): JsonResponse
	{
		$params = $request->only([
			'parent_id',
			'type',
			'name',
			'icon',
			'uri',
			'path',
			'method',
			'component',
			'is_show',
			'is_disable',
			'rank'
		]);
		$permission->fill($params)->save();
		PermissionService::cacheClear();
		return $this->success();
	}

	/**
	 * 删除
	 *
	 * @param Permission $permission
	 * @return JsonResponse
	 * @throws ForbiddenException
	 */
	public function destroy(Permission $permission): JsonResponse
	{
		// 获取是否存在子级
		$child = Permission::query()->where('parent_id', $permission->id)->first();
		if ($child) {
			throw new ForbiddenException(__('messages.has_child'));
		}
		$permission->delete();
		PermissionService::cacheClear();

		return $this->success();
	}

	/**
	 * 树状结构数据
	 *
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function tree(Request $request): JsonResponse
	{
		$isCache = (bool)$request->get('is_cache');
		return $this->response(PermissionService::cacheResult(isCache: $isCache));
	}

	/**
	 * 权限后端路由列表
	 * @return JsonResponse
	 */
	public function routeAction(): JsonResponse
	{
		$routes = Route::getRoutes();
		$lists  = [];
		foreach ($routes as $route) {
			if ($route->getPrefix() !== 'admin-api') {
				continue;
			}
			if (in_array('permission.admin', $route->excludedMiddleware())) {
				continue;
			}
			$lists[] = [
				'uri'    => $route->uri(),
				'method' => $route->methods()[0] ?? '',
				'name'   => $route->getName(),
			];
		}

		return $this->response($lists);
	}
}
