<?php

use App\Http\Api\Controllers\EnumController;
use App\Http\UserApi\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/**
 * 授权
 */
Route::post('/auth/login/wechat-mini-program', [AuthController::class, 'loginByMiniProgram'])->withoutMiddleware('auth:user'); // 微信小程序登录
Route::post('/auth/login', [AuthController::class, 'login'])->withoutMiddleware('auth:sanctum'); // 登录
Route::post('/auth/refresh', [AuthController::class, 'refresh']); // 刷新token
Route::get('/auth/me', [AuthController::class, 'me'])->middleware('auth:sanctum'); // 个人信息
Route::delete('/auth/logout', [AuthController::class, 'logout']); // 退出登录

/**
 * 枚举
 */
Route::get('/enums/{enum}', EnumController::class)->withoutMiddleware(['auth:admin']); // 拥有的权限菜单
