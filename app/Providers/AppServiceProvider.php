<?php

namespace App\Providers;

use App\Enums\Model\ModelAliasEnum;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
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
	}
}
