<?php

namespace App\Providers;

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
        // app/Exceptions/Handler.php 那里默认返回 config('app.locale'),这样做，避免返回默认的语言
        if(!app()->runningInConsole()) {
            $locale = request()->header('Accept-Language');
            if ($locale) {
                app()->setLocale($locale);
            }
        }
    }
}
