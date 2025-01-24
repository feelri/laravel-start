<?php

use Illuminate\Foundation\Application;

$routeThenCallback = include 'with/route-then.php';
$middlewareCallback = include 'with/middleware.php';
$exceptionsCallback = include 'with/exceptions.php';

return Application::configure(basePath: dirname(__DIR__))
    // 初始化路由
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
        then: $routeThenCallback
    )

    // 注册全局中间件
    ->withMiddleware($middlewareCallback)

    // 异常处理
    ->withExceptions($exceptionsCallback)
    ->create();
