<?php

namespace App\Http\Middleware;

use App\Helpers\LocalesHelper;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->session()->get('language', 'en');
        if (is_string($locale) && in_array($locale, LocalesHelper::locales(), true))
        {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
