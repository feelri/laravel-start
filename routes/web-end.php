<?php

use Illuminate\Support\Facades\Route;

// 空路由规则
Route::fallback(fn () => 'Hi, Please respect the technology');
