<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class Json
 * @package App\Http\Middleware
 */
class Json
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (!$request->expectsJson()) {
            throw new BadRequestHttpException(trans('errors.http.400'));
        }

        return $next($request);
    }
}
