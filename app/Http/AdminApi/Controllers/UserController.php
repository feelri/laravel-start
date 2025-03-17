<?php

namespace App\Http\AdminApi\Controllers;

use App\Enums\PaginateEnum;
use App\Models\User\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * 用户
 */
class UserController extends Controller
{
	/**
	 * 列表
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function index(Request $request): JsonResponse
	{
		$params = $request->only(['keyword', 'limit']);
		$users = User::query();

		if (!empty($params['keyword'])) {
			$users->where(function ($query) use ($params) {
				$query->where('mobile', 'like', "%{$params['keyword']}%")
					->orWhere('name', 'like', "%{$params['keyword']}%")
					->orWhere('nickname', 'like', "%{$params['keyword']}%");
			});
		}

		$users = $users->orderBy('id', 'desc')
			->paginate($params['limit'] ?? PaginateEnum::Default->value);
		return $this->response($users);
	}

	/**
	 * 详情
	 *
	 * @param User $user
	 * @return JsonResponse
	 */
	public function show(User $user): JsonResponse
	{
		return $this->response($user);
	}
}
