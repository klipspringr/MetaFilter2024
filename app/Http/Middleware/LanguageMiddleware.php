<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class LanguageMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $language = session('language');

        app()->setLocale($language);

        return $next($request);
    }
}
