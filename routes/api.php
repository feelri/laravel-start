<?php

use App\Http\Api\Controllers\CaptchaController;
use App\Http\Api\Controllers\DistrictController;
use App\Http\Api\Controllers\EnumController;
use App\Http\Api\Controllers\FileUploadController;
use Illuminate\Support\Facades\Route;

Route::get('districts/tree', [DistrictController::class, 'tree'])->name('地区树');

Route::get('districts', [DistrictController::class, 'index'])->name('地区列表');

Route::get('captcha', [CaptchaController::class, 'index'])->name('图形验证码');

Route::get('file-upload/credentials', [FileUploadController::class, 'credentials'])->name('上传凭证');

Route::post('file-uploads', [FileUploadController::class, 'store'])->name('本地上传');

Route::get('/enums/{enum}', EnumController::class)->name('枚举列表');

