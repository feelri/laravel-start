<?php

use App\Http\Api\Controllers\CaptchaController;
use App\Http\Api\Controllers\DistrictController;
use App\Http\Api\Controllers\FileUploadController;
use Illuminate\Support\Facades\Route;

// 地区树
Route::get('districts/tree', [DistrictController::class, 'tree']);

// 地区列表
Route::get('districts', [DistrictController::class, 'index']);

// 图形验证码
Route::get('captcha', [CaptchaController::class, 'index']);

// 上传凭证
Route::get('file-upload/credentials', [FileUploadController::class, 'credentials']);

// 本地上传
Route::post('file-uploads', [FileUploadController::class, 'store']);
