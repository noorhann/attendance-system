<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Closure;
use Illuminate\Http\Response;
class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    public function handle($request, Closure $next , ...$guards)
    {

        if (auth()->guard('api')->check() ) {
            return $next($request);
        }

        return apiResponse(
            false,
            'unauthenticated user',
            Response::HTTP_UNAUTHORIZED
        );
    }
}
