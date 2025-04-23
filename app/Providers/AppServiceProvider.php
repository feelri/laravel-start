<?php

namespace App\Providers;

use App\Enums\Model\ModelAliasEnum;
use App\Listeners\BroadcastMessage;
use App\Rewrite\LengthAwarePaginator;
use Illuminate\Pagination\LengthAwarePaginator as LaravelLengthAwarePaginator;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
	public array $bindings = [
		// 分页类绑定
		LaravelLengthAwarePaginator::class => LengthAwarePaginator::class,
	];

	/**
	 * Register any application services.
	 */
	public function register(): void
	{
		//
	}

	/**
	 * Bootstrap any application services.
	 */
	public function boot(): void
	{
		// 模型别名
		Relation::enforceMorphMap(ModelAliasEnum::maps());

		// 监听 reverb 消息
		Event::listen(BroadcastMessage::class,);
	}
}
