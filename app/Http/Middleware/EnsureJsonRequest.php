<?php

namespace App\Http\Middleware;

use App\Enums\APICodeEnum;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureJsonRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isJson()) {
            $raw = $request->getContent();
            if ($raw) {
                json_decode($raw, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    return api_res(APICodeEnum::EXCEPTION, 'Invalid JSON format', [], 400);
                }
            }
        }

        return $next($request);
    }
}
