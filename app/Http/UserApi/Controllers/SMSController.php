<?php

namespace App\Http\UserApi\Controllers;

use App\Http\Api\Controllers\Controller;
use App\Http\UserApi\Requests\VerifyRequest;
use App\Services\Sms\SmsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

/**
 * 发送短信
 */
class SMSController extends Controller
{
	/**
	 * 登录验证码
	 *
	 * @param VerifyRequest $request
	 * @return JsonResponse
	 */
	public function loginVerify(VerifyRequest $request): JsonResponse
	{
		$mobile = $request->input('mobile');
		$code = rand(100000, 999999);
		SmsService::static()->code('SMS_318180480')->sendByMobile($mobile, ['code'=>$code]);
		Cache::set("sms:loginCode:{$mobile}", $code, 15 * 60); // 15分钟
		return $this->success("验证码已发送，请注意查收");
	}

	/**
	 * 登录验证码
	 *
	 * @param VerifyRequest $request
	 * @return JsonResponse
	 */
	public function updateMobileVerify(VerifyRequest $request): JsonResponse
	{
		$mobile = $request->input('mobile');
		$code = rand(100000, 999999);
		SmsService::static()->code('SMS_318180480')->sendByMobile($mobile, ['code'=>$code]);
		Cache::set("sms:updateMobile:{$mobile}", $code, 15 * 60); // 15分钟
		return $this->success("验证码已发送，请注意查收");
	}
}
