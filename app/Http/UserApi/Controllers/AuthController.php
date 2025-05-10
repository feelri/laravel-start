<?php

namespace App\Http\UserApi\Controllers;

use App\Enums\HTTPCodeEnum;
use App\Enums\Model\ModelAliasEnum;
use App\Enums\Model\UserOauthFromEnum;
use App\Exceptions\AuthException;
use App\Exceptions\ForbiddenException;
use App\Exceptions\ParameterException;
use App\Exceptions\ResourceException;
use App\Http\UserApi\Requests\Auth\RepassRequest;
use App\Http\UserApi\Requests\Auth\UpdateMeRequest;
use App\Http\UserApi\Requests\Auth\AuthLoginRequest;
use App\Http\UserApi\Requests\Auth\AuthRegisterRequest;
use App\Models\Oauth;
use App\Models\User\User;
use App\Services\Cloud\Tencent\WechatMiniProgramService;
use App\Services\OpenSSLTokenService;
use EasyWeChat\Kernel\Exceptions\BadResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\NewAccessToken;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

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
		$params = $request->only(['mobile', 'password']);
		$user = User::query()->where('mobile', $params['mobile'])->first();
		if (!$user) {
			throw new ResourceException(__('message.login.auth_fail'));
		}

		if (!Hash::check($params['password'], $user->password)) {
			throw new ResourceException(__('message.login.auth_fail'));
		}

		// 生成 token
		$token = DB::transaction(function () use ($user) {
			$user->last_login_at = now();

			$tokenName = 'auth';
			$user->tokens()->where('name', $tokenName)->delete();
			return $user->createToken($tokenName);
		});
		$data  = $this->dataWithToken($token);
		return $this->response($data, __('messages.login.success'));
	}

	/**
	 * 注册
	 *
	 * @param AuthRegisterRequest $request
	 * @return JsonResponse
	 * @throws ForbiddenException
	 * @throws ResourceException
	 */
	public function register(AuthRegisterRequest $request): JsonResponse
	{
		$params = $request->only(['name', 'nickname', 'avatar', 'mobile', 'raw']);
		$user = User::query()->where('mobile', $params['mobile'])->first();
		if ($user) {
			throw new ResourceException('账号已被使用');
		}

		$rawData = (new OpenSSLTokenService())->decrypt($params['raw']);
		if (!$rawData) {
			throw new ForbiddenException('非法操作');
		}

		$token = DB::transaction(function () use ($params, $rawData) {
			$user = User::query()->create([
				'name'          => $params['name'],
				'nickname'      => $params['nickname'],
				'avatar'        => $params['avatar'],
				'mobile'        => $params['mobile'],
				'last_login_at' => now()
			]);

			$user->oauth()->create($rawData);
			$user->last_login_at = now();
			$tokenName           = 'auth';
			$user->tokens()->where('name', $tokenName)->delete();
			return $user->createToken($tokenName);
		});
		$data  = $this->dataWithToken($token);
		return $this->response($data, '注册成功已自动登录');
	}

	/**
	 * 微信小程序登录
	 *
	 * @param Request $request
	 * @return JsonResponse
	 * @throws AuthException
	 * @throws BadResponseException
	 * @throws ClientExceptionInterface
	 * @throws DecodingExceptionInterface
	 * @throws ParameterException
	 * @throws RedirectionExceptionInterface
	 * @throws ServerExceptionInterface
	 * @throws TransportExceptionInterface
	 */
	public function loginByWechatMiniProgram(Request $request): JsonResponse
	{
		$code = $request->input('code');
		if (empty($code)) {
			throw new ParameterException();
		}
		$session = (new WechatMiniProgramService)->code2Session($code);
		if (!empty($session['errcode'])) {
			throw new AuthException("授权失败：{$session['errmsg']}");
		}

		$where = [
			'from'        => UserOauthFromEnum::WechatMiniProgram->value,
			'source_type' => ModelAliasEnum::User->getName(),
			'openid'      => $session['openid'],
		];
		$oauth = Oauth::query()->with('source')->where($where)->first();
		if (!$oauth || !$oauth->source) {
			$raw = (new OpenSSLTokenService)->encrypt($where);
			return $this->response(['raw'=>$raw]);
		}

		// 生成 token
		$tokenName = 'auth';
		$data  = $this->dataWithToken($oauth->source->createToken($tokenName));
		return $this->response($data, __('messages.login.success'));
	}

	/**
	 * 绑定微信小程序
	 *
	 * @param Request $request
	 * @return JsonResponse
	 * @throws AuthException
	 * @throws BadResponseException
	 * @throws ClientExceptionInterface
	 * @throws DecodingExceptionInterface
	 * @throws ParameterException
	 * @throws RedirectionExceptionInterface
	 * @throws ServerExceptionInterface
	 * @throws TransportExceptionInterface
	 */
	public function bindByWechatMinProgram(Request $request): JsonResponse
	{
		$worker = $this->worker;
		$worker->load(['wechatMiniProgramOauth']);

		if ($worker->wechatMiniProgramOauth) {
			return $this->success();
		}
		$code = $request->input('code');
		if (empty($code)) {
			throw new ParameterException();
		}
		$session = (new WechatMiniProgramService)->code2Session($code);
		if (!empty($session['errcode'])) {
			return $this->fail("授权过期，绑定失败");
		}

		$oauth = Oauth::query()->where('openid', $session['openid'])->first();
		if ($oauth) {
			return $this->fail("此微信已绑定其他账户");
		}

		$worker->wechatMiniProgramOauth()->create(array_filter([
			'from'			=> UserOauthFromEnum::WechatMiniProgram->value,
			'source_type'	=> ModelAliasEnum::User->getName(),
			'source_id'		=> $worker->id,
			'openid'		=> $session['openid'],
			'unionid'		=> $session['unionid'] ?? ''
		]));

		return $this->success();
	}

	/**
	 * Refresh a token.
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
	 * Get the authenticated User.
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function me(Request $request): JsonResponse
	{
		$user = $request->user();
		$user->load(['level']);
		return $this->response($user);
	}

	/**
	 * Log the user out (Invalidate the token).
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function logout(Request $request): JsonResponse
	{
		$request->user()->currentAccessToken()->delete();
		return $this->response(__('messages.login.out'));
	}

	/**
	 * 更新当前用户信息
	 *
	 * @param UpdateMeRequest $request
	 * @return JsonResponse
	 */
	public function updateMe(UpdateMeRequest $request): JsonResponse
	{
		$params         = $request->only(['name', 'nickname', 'avatar', 'gender']);
		$worker         = $request->user();

		if (!empty($params['name'])) {
			$worker->name   = $params['name'];
		}

		if (!empty($params['nickname'])) {
			$worker->nickname = $params['nickname'];
		}

		if (!empty($params['avatar'])) {
			$worker->avatar = $params['avatar'];
		}

		if (!empty($params['gender'])) {
			$worker->gender = $params['gender'];
		}

		$worker->save();
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
		$params           = $request->only(['old_password', 'new_password']);
		$worker           = $request->user();
		$worker->password = Hash::make($params['new_password']);
		$worker->save();

		return $this->success();
	}

	/**
	 * 修改手机号
	 *
	 * @param Request $request
	 * @return JsonResponse
	 * @throws ResourceException
	 */
	public function updateMobile(Request $request): JsonResponse
	{
		$params          = $request->only(['mobile', 'code']);
		if (Cache::get("sms:updateMobile:{$params['mobile']}") != $params['code']) {
			throw new ResourceException("验证码有误");
		}

		$worker          = $request->user();
		$worker->account = $params['mobile'];
		$worker->save();
		return $this->success();
	}

	/**
	 * 获取 token
	 * @param NewAccessToken  $token
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
