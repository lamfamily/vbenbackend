<?php

namespace App\Providers;

use App\Services\Translation\Json5Translator;
use Illuminate\Support\ServiceProvider;

class Json5TranslationServiceProvider extends ServiceProvider
{
    /**
     * 注册应用程序服务
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('json5.translator', function ($app) {
            return new Json5Translator($app['files']);
        });
    }

    /**
     * 启动应用程序服务
     *
     * @return void
     */
    public function boot()
    {
    }
}
