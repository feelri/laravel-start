<?php

namespace App\Http\AdminApi\Controllers;

use App\Enums\BoolIntEnum;
use App\Enums\PaginateEnum;
use App\Exceptions\ForbiddenException;
use App\Http\AdminApi\Requests\Admin\SaveRequest;
use App\Models\Admin\Admin;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * 员工
 */
class AdminController extends Controller
{
	/**
	 * 列表
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function index(Request $request): JsonResponse
	{
		$params = $request->only(['keyword', 'limit']);
		$admins = Admin::query()->with(['roles:id,name']);

		if (!empty($params['keyword'])) {
			$admins->where(function ($query) use ($params) {
				$query->where('account', 'like', "%{$params['keyword']}%")
					->orWhere('mobile', 'like', "%{$params['keyword']}%")
					->orWhere('name', 'like', "%{$params['keyword']}%")
					->orWhere('nickname', 'like', "%{$params['keyword']}%");
			});
		}

		$admins = $admins->paginate($params['limit'] ?? PaginateEnum::Default->value);
		return $this->response($admins);
	}

	/**
	 * 新增
	 *
	 * @param SaveRequest $request
	 * @return JsonResponse
	 */
	public function store(SaveRequest $request): JsonResponse
	{
		$params = $request->only(['account', 'mobile', 'password', 'name', 'nickname', 'avatar', 'gender', 'is_disable', 'role_ids']);

		$admin = DB::transaction(function () use ($params) {
			$admin  = Admin::query()->create([
				'account'    => $params['account'],
				'mobile'     => $params['mobile'] ?? null,
				'password'   => Hash::make($params['password']),
				'name'       => $params['name'],
				'nickname'   => $params['nickname'] ?? '',
				'avatar'     => $params['avatar'] ?? '',
				'gender'     => $params['gender'] ?? 0,
				'is_disable' => $params['is_disable'] ?? 0,
			]);
			$admin->roles()->sync($params['role_ids']);
			return $admin;
		});

		return $this->response(['id' => $admin->id]);
	}

	/**
	 * 详情
	 *
	 * @param Admin $admin
	 * @return JsonResponse
	 */
	public function show(Admin $admin): JsonResponse
	{
		$admin->offsetSet('role_ids', $admin->roles?->pluck('id')->toArray() ?? []);
		return $this->response($admin);
	}

	/**
	 * 修改
	 *
	 * @param SaveRequest $request
	 * @param Admin       $admin
	 * @return JsonResponse
	 */
	public function update(SaveRequest $request, Admin $admin): JsonResponse
	{
		$params         = $request->only(['account', 'mobile', 'password', 'name', 'nickname', 'avatar', 'gender', 'is_disable', 'role_ids']);
		$admin->account = $params['account'];
		$admin->mobile  = $params['mobile'];
		if (!empty($params['password'])) {
			$admin->password = Hash::make($params['password']);
		}
		$admin->name       = $params['name'];
		$admin->nickname   = $params['nickname'] ?? '';
		$admin->avatar     = $params['avatar'] ?? '';
		$admin->gender     = $params['gender'] ?? 0;
		$admin->is_disable = $params['is_disable'] ?? 0;

		DB::transaction(function () use ($admin, $params) {
			$admin->save();
			$admin->roles()->sync($params['role_ids']);
		});
		return $this->success();
	}

	/**
	 * 删除
	 *
	 * @param Admin $admin
	 * @return JsonResponse
	 * @throws ForbiddenException
	 */
	public function destroy(Admin $admin): JsonResponse
	{
		if ($admin->id === BoolIntEnum::True->value) {
			throw new ForbiddenException(__('messages.admin_can_not_delete'));
		}
		$admin->delete();
		return $this->success();
	}
}
