<?php

namespace App\Providers;

use App\Enums\Model\ModelAliasEnum;
use App\Services\ToolService;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\App;
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
		$this->setLocale();

		// 模型别名
		Relation::enforceMorphMap(ModelAliasEnum::maps());
    }

	/**
	 * 本地化
	 * @return void
	 */
	public function setLocale(): void
	{
		$request = $this->app->request;
		$language = $request->input('language');
		if (empty($language)) {
			$acceptLanguage = $request->header('accept-language');
			$language = ToolService::static()->getPreferredLanguage($queryLanguage ?? $acceptLanguage);
		}
		App::setLocale($language ?? 'zh-CN');
	}
}
