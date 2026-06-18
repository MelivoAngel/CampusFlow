<?php

namespace App\Domain\Auth\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CampusScopeMiddleware
{
    public function handle(
        Request $request,
        Closure $next
    ): Response
    {
        return $next($request);
    }
}