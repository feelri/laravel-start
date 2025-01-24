<?php

use Illuminate\Support\Facades\Route;

$routeConfigs = include __DIR__ . '/other-route-config.php';

/**
 * 路由注册
 */
return function () use ($routeConfigs) {
    // 注册其他模块路由
    foreach ($routeConfigs['data'] as $routeConfig) {
        Route::middleware($routeConfig['middleware'])
			->prefix($routeConfig['prefix'])
			->group($routeConfig['group']);
    }
};
