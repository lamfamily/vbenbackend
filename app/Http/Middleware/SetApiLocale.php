<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetApiLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 获取请求头中的语言参数
        $locale = $request->header('Accept-Language');


        // 如果语言参数存在且在支持的语言列表中，则设置应用程序的语言
        if ($locale && in_array($locale, config('app.supported_locales'))) {
            app()->setLocale($locale);
        } else {
            // 如果没有提供语言参数，则使用默认语言
            app()->setLocale(config('app.fallback_locale'));
        }

        return $next($request);
    }
}
