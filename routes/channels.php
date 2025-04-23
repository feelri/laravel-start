<?php

use App\Broadcasting\TestChannel;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

Broadcast::routes(['middleware' => ['api', 'auth:user']]);

Broadcast::channel('App.Models.User.1', function () {
	Log::error("校验是否有访问频道权限: ");
	return true;
}, ['guards' => ['api321', 'auth:user']]);
