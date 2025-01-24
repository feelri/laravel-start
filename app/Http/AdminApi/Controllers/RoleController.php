<?php

namespace App\Http\AdminApi\Controllers;

use App\Enums\BoolIntEnum;
use App\Enums\PaginateEnum;
use App\Exceptions\ForbiddenException;
use App\Http\AdminApi\Requests\Role\SaveRequest;
use App\Models\Admin\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * 角色
 */
class RoleController extends Controller
{
	/**
	 * 列表
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function index(Request $request): JsonResponse
	{
		$params      = $request->only(['keyword', 'limit']);
		$roles = Role::query();

		if (!empty($params['keyword'])) {
			$roles->where(function ($query) use ($params) {
				$query->where('name', 'like', "%{$params['keyword']}%")
					->orWhere('description', 'like', "%{$params['keyword']}%");
			});
		}

		$roles = $roles->where('is_top_level', BoolIntEnum::False->value)
			->withCount(['admins', 'permissions'])
			->paginate($params['limit'] ?? PaginateEnum::Default->value);
		return $this->response($roles);
	}

	/**
	 * 新增
	 *
	 * @param SaveRequest $request
	 * @return JsonResponse
	 */
	public function store(SaveRequest $request): JsonResponse
	{
		$params = $request->only(['name', 'description', 'rank', 'permission_ids']);

		$role = DB::transaction(function () use ($params) {
			$role = Role::query()->create([
				'name'        => $params['name'],
				'description' => $params['description'] ?? '',
				'rank'        => $params['rank'] ?? 0,
			]);
			$role->permissions()->sync($params['permission_ids']);
			return $role;
		});
		return $this->response(['id' => $role->id]);
	}

	/**
	 * 详情
	 *
	 * @param Role $role
	 * @return JsonResponse
	 */
	public function show(Role $role): JsonResponse
	{
		$role->load('permissions');
		$role->offsetSet('permission_ids', $role->permissions?->pluck('id')->toArray() ?? []);
		return $this->response($role);
	}

	/**
	 * 修改
	 *
	 * @param SaveRequest $request
	 * @param Role    $role
	 * @return JsonResponse
	 */
	public function update(SaveRequest $request, Role $role): JsonResponse
	{
		$params = $request->only(['name', 'description', 'rank', 'permission_ids']);

		DB::transaction(function () use ($role, $params) {
			$role->name = $params['name'];
			$role->description = $params['description'] ?? '';
			$role->rank = $params['rank'] ?? 0;
			$role->save();
			$role->permissions()->sync($params['permission_ids']);
		});
		return $this->success();
	}

	/**
	 * 删除
	 *
	 * @param Role $role
	 * @return JsonResponse
	 * @throws ForbiddenException
	 */
	public function destroy(Role $role): JsonResponse
	{
		if ((int) $role->is_top_level === BoolIntEnum::True->value) {
			throw new ForbiddenException(__('messages.role_can_not_delete'));
		}

		DB::transaction(function () use ($role) {
			$role->permissions()->detach();
			$role->delete();
		});
		return $this->success();
	}
}
