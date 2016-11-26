<?php

namespace Api\V1\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class AllowCorsRequests
 * @package Api\V1\Http\Middleware
 */
class AllowCorsRequests
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string|null $guard
     * @return Response
     */
    public function handle($request, Closure $next, $guard = null)
    {
        /** @var Response $response */

        $response = $next($request);

        $response
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE')
            ->header('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With');

        return $response;
    }
}
