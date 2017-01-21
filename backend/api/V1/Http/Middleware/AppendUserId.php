<?php

namespace Api\V1\Http\Middleware;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Closure;

/**
 * Class AppendCurrentUserId.
 *
 * @property Guard guard
 * @package Api\V1\Http\Middleware
 */
class AppendUserId
{
    /**
     * UserIdAppender constructor.
     *
     * @param Guard $guard
     */
    public function __construct(Guard $guard)
    {
        $this->guard = $guard;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request->merge(['user_id' => $this->guard->user()->id]);

        return $next($request);
    }
}
