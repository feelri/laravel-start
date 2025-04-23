<?php

use App\Http\Api\Controllers\EnumController;
use App\Http\UserApi\Controllers\AuthController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * 授权
 */
Route::post('/auth/login/wechat-mini-program', [AuthController::class, 'loginByWechatMiniProgram'])->withoutMiddleware('auth:user'); // 微信小程序登录
Route::post('/auth/login', [AuthController::class, 'login'])->withoutMiddleware('auth:user'); // 登录
Route::post('/auth/register', [AuthController::class, 'register'])->withoutMiddleware('auth:user')->withoutMiddleware('auth:user'); // 注册
Route::post('/auth/refresh', [AuthController::class, 'refresh']); // 刷新token
Route::get('/auth/me', [AuthController::class, 'me']); // 个人信息
Route::delete('/auth/logout', [AuthController::class, 'logout']); // 退出登录
Route::put('/auth/me', [AuthController::class, 'updateMe'])->withoutMiddleware(['shop_check'])->name('个人信息修改');
Route::put('/auth/password', [AuthController::class, 'repass'])->withoutMiddleware(['shop_check'])->name('修改密码');
Route::put('/auth/mobile', [AuthController::class, 'updateMobile'])->withoutMiddleware(['shop_check'])->middleware('throttle:10,5')->name('修改手机号');
Route::post('/auth/bind-wechat-mini-program', [AuthController::class, 'bindByWechatMinProgram'])->withoutMiddleware(['shop_check'])->name('微信小程序绑定');

/**
 * 枚举
 */
Route::get('/enums/{enum}', EnumController::class)->withoutMiddleware(['auth:user']); // 拥有的权限菜单


Route::post('/broadcasting/auth', function (Request $request) {
// channel name will be something like private-room-231 where 231 is accountId
	$socketId = $request->input('socket_id');
	$channelName = $request->input('channel_name');

	$channelPieces = explode('-', $channelName);
	$channelAccount = end($channelPieces);

	// this verifies account id against a server known value
//	if ($channelAccount != $request->attributes->get('auth_account_id')) {
//		return new JsonResponse(['auth' => 'INVALID'], 403);
//	}

	// this generates the required format for the response
	$stringToAuth = $socketId . ':' . $channelName;
	$hashed = hash_hmac('sha256', $stringToAuth, env('REVERB_APP_SECRET'));

	return new JsonResponse(['auth' => env('REVERB_APP_KEY') . ':' . $hashed]);
});
