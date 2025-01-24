<?php

namespace App\Http\AdminApi\Controllers;

use App\Exceptions\ForbiddenException;
use App\Exceptions\ResourceException;
use App\Http\AdminApi\Requests\Auth\AuthLoginRequest;
use App\Http\AdminApi\Requests\Auth\RepassRequest;
use App\Http\AdminApi\Requests\Auth\UpdateMeRequest;
use App\Models\Admin\Admin;
use App\Services\Model\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\NewAccessToken;
use Illuminate\Support\Facades\DB;

/**
 * 授权
 */
class AuthController extends Controller
{
	/**
	 * 登录
	 *
	 * @param AuthLoginRequest $request
	 * @return JsonResponse
	 * @throws ResourceException
	 */
	public function login(AuthLoginRequest $request): JsonResponse
	{
		$params = $request->only(['account', 'password']);
		$admin  = Admin::query()->where('account', $params['account'])->first();
		if (!$admin) {
			throw new ResourceException(__('message.login.auth_fail'));
		}

		if (!Hash::check($params['password'], $admin->password)) {
			throw new ResourceException(__('messages.login.auth_fail'));
		}

		// 生成 token
		$token = DB::transaction(function () use ($admin) {
			$tokenName = 'auth';
			$admin->tokens()->where('name', $tokenName)->delete();
			return $admin->createToken($tokenName);
		});
		$data  = $this->dataWithToken($token);

		// 记录登录时间
		$admin->last_login_at = now();
		$admin->save();

		return $this->response($data, __('messages.login.success'));
	}

	/**
	 * 刷新 token
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function refresh(Request $request): JsonResponse
	{
		$token = $request->user()->refreshCurrentToken();
		$data = $this->dataWithToken($token);
		return $this->response($data);
	}

	/**
	 * 当前用户信息
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function me(Request $request): JsonResponse
	{
		$admin = $request->user();
		$admin->load(['roles']);
		return $this->response($admin);
	}

	/**
	 * 更新当前用户信息
	 *
	 * @param UpdateMeRequest $request
	 * @return JsonResponse
	 */
	public function updateMe(UpdateMeRequest $request): JsonResponse
	{
		$params          = $request->only(['name', 'nickname', 'avatar', 'gender']);
		$admin           = $request->user();
		$admin->name     = $params['name'];
		$admin->nickname = $params['nickname'] ?? '';
		$admin->avatar   = $params['avatar'] ?? '';
		$admin->gender   = $params['gender'] ?? 0;
		$admin->save();

		return $this->success();
	}

	/**
	 * 修改密码
	 *
	 * @param RepassRequest $request
	 * @return JsonResponse
	 */
	public function repass(RepassRequest $request): JsonResponse
	{
		$params          = $request->only(['old_password', 'new_password']);
		$admin           = $request->user();
		$admin->password = Hash::make($params['new_password']);
		$admin->save();

		return $this->success();
	}

	/**
	 * 修改手机号
	 *
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function updateMobile(Request $request): JsonResponse
	{
		$params         = $request->only(['mobile', 'sms_code']);
		$admin          = $request->user();
		$admin->account = $params['mobile'];
		$admin->mobile  = $params['mobile'];
		$admin->save();
		return $this->success();
	}

	/**
	 * 退出登录
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function logout(Request $request): JsonResponse
	{
		$request->user()->currentAccessToken()->delete();
		return $this->response(__('messages.login.out'));
	}

	/**
	 * 用户权限
	 *
	 * @param Request $request
	 * @return JsonResponse
	 * @throws ForbiddenException
	 */
	public function permissions(Request $request): JsonResponse
	{
		$admin             = $request->user();
		$permissionService = new PermissionService();
		return $this->response($permissionService->from($admin)->getAllPermissions());
	}

	/**
	 * 获取 token
	 * @param NewAccessToken $token
	 * @return array
	 */
	protected function dataWithToken(NewAccessToken $token): array
	{
		return [
			'access_token' => $token->plainTextToken,
			'token_type'   => 'bearer',
			'expires_at'   => $token->accessToken?->expires_at?->toDateTimeString(),
		];
	}
}
