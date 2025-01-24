<?php

namespace App\Http\UserApi\Controllers;

use App\Enums\Model\UserOauthFromEnum;
use App\Exceptions\ParameterException;
use App\Exceptions\ResourceException;
use App\Http\UserApi\Requests\Auth\AuthLoginRequest;
use App\Models\User\User;
use App\Models\User\UserOauth;
use App\Services\Cloud\Tencent\WechatMiniProgramService;
use EasyWeChat\Kernel\Exceptions\BadResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $params = $request->only(['account', 'password']);
        $user = User::query()->where('account', $params['account'])->first();
        if (!$user) {
            throw new ResourceException(__('message.login.auth_fail'));
        }

        if (!Hash::check($params['password'], $user->password)) {
            throw new ResourceException(__('message.login.auth_fail'));
        }

        // 生成 token
		$token = DB::transaction(function () use ($user) {
			$tokenName = 'auth';
			$user->tokens()->where('name', $tokenName)->delete();
			return $user->createToken($tokenName);
		});
		$data  = $this->dataWithToken($token);
        return $this->response($data, __('messages.login.success'));
    }

	/**
	 * 小程序登录
	 *
	 * @param Request $request
	 * @return JsonResponse
	 * @throws ParameterException
	 * @throws BadResponseException
	 * @throws ClientExceptionInterface
	 * @throws DecodingExceptionInterface
	 * @throws RedirectionExceptionInterface
	 * @throws ServerExceptionInterface
	 * @throws TransportExceptionInterface
	 */
	public function loginByMiniProgram(Request $request): JsonResponse
	{
		$code = $request->input('code');
		if (empty($code)) {
			throw new ParameterException();
		}
		$session = (new WechatMiniProgramService)->code2Session($code);

		$where = [
			'from'   => UserOauthFromEnum::WechatMiniProgram->value,
			'openid' => $session['openid'],
		];
		$oauth = UserOauth::query()->with('user')->where($where)->first();
		$user = $oauth->user;
		if (!$oauth) {
			$user = DB::transaction(function () use ($session, $where) {
				$user = (new User)->createDefault();
				$user->oauth()->create([...$where, 'unionid' => $session['unionid']]);
				return $user;
			});
		}

		// 生成 token
		$token = DB::transaction(function () use ($user) {
			$tokenName = 'auth';
			$user->tokens()->where('name', $tokenName)->delete();
			return $user->createToken($tokenName);
		});
		$data = $this->dataWithToken($token);
		return $this->response($data, __('messages.login.success'));
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
        return $this->response($request->user());
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
