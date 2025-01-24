<?php

use App\Enums\Model\ConfigKeyEnum;
use App\Http\AdminApi\Controllers\AdminController;
use App\Http\AdminApi\Controllers\AuthController;
use App\Http\AdminApi\Controllers\ConfigController;
use App\Http\AdminApi\Controllers\DictionaryController;
use App\Http\AdminApi\Controllers\DictionaryItemController;
use App\Http\AdminApi\Controllers\EnumController;
use App\Http\AdminApi\Controllers\FileUploadController;
use App\Http\AdminApi\Controllers\PermissionController;
use App\Http\AdminApi\Controllers\RoleController;
use App\Http\AdminApi\Controllers\SmsLogController;
use Illuminate\Support\Facades\Route;

/**
 * 无需权限验证路由组
 */
Route::withoutMiddleware(['permission.admin'])->group(function () {
	/**
	 * 授权
	 */
	Route::post('/auth/login', [AuthController::class, 'login'])->withoutMiddleware(['auth:admin'])->name('授权登录');
	Route::post('/auth/refresh', [AuthController::class, 'refresh'])->name('刷新token');
	Route::get('/auth/me', [AuthController::class, 'me'])->name('个人信息');
	Route::put('/auth/me', [AuthController::class, 'updateMe'])->name('个人信息修改');
	Route::put('/auth/password', [AuthController::class, 'repass'])->name('修改密码');
	Route::put('/auth/mobile', [AuthController::class, 'updateMobile'])->name('修改手机号');
	Route::delete('/auth/logout', [AuthController::class, 'logout'])->name('退出登录');
	Route::get('/auth/permissions', [AuthController::class, 'permissions'])->name('用户拥有权限菜单');

	Route::get('/enums/{enum}', EnumController::class)->withoutMiddleware(['auth:admin'])->name('枚举列表');
	Route::get('/permissions/route-action', [PermissionController::class, 'routeAction'])->name('权限后端路由列表');
});

/**
 * 权限菜单
 */
Route::get('/permissions/tree', [PermissionController::class, 'tree'])->name('权限树状结构数据');
Route::apiResource('permissions', PermissionController::class)->names([
	'index'   => '权限列表',
	'store'   => '权限创建',
	'update'  => '权限修改',
	'show'    => '权限详情',
	'destroy' => '权限删除',
]);

/**
 * 角色
 */
Route::apiResource('roles', RoleController::class)->names([
	'index'   => '角色列表',
	'store'   => '角色创建',
	'update'  => '角色修改',
	'show'    => '角色详情',
	'destroy' => '角色删除',
]);

/**
 * 员工
 */
Route::apiResource('admins', AdminController::class)->names([
	'index'   => '员工列表',
	'store'   => '员工创建',
	'update'  => '员工修改',
	'show'    => '员工详情',
	'destroy' => '员工删除',
]);

/**
 * 分类
 */
Route::get('/categories/tree', [ConfigController::class, 'tree'])->name('分类树状结构数据');
Route::apiResource('categories', ConfigController::class)->names([
	'index'   => '分类列表',
	'store'   => '分类创建',
	'update'  => '分类修改',
	'show'    => '分类详情',
	'destroy' => '分类删除',
]);

/**
 * 文件上传列表
 */
Route::apiResource('file-uploads', FileUploadController::class)
	->only(['index', 'show', 'update', 'destroy'])
	->names([
		'index'   => '文件上传列表',
		'update'  => '文件上传修改',
		'show'    => '文件上传详情',
		'destroy' => '文件上传删除',
	]);

/**
 * 配置
 */
Route::get('configs/{key}/detail', [ConfigController::class, 'detail'])
	->whereIn('key', ConfigKeyEnum::values())
	->withoutMiddleware(['auth:admin'])
	->name('配置详情');
Route::put('configs/{key}/detail', [ConfigController::class, 'save'])
	->whereIn('key', ConfigKeyEnum::values())
	->name('保存配置');

/**
 * 字典
 */
Route::apiResource('dictionary-items', DictionaryItemController::class)->only(['index', 'store', 'show', 'update', 'destroy']);

Route::apiResource('dictionaries', DictionaryController::class)->only(['index', 'store', 'show', 'update', 'destroy']);

/**
 * 短信日志
 */
Route::get('sms-logs', SmsLogController::class)->name('短信日志列表');
