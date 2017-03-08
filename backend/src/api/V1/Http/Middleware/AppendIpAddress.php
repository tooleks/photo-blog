<?php

namespace Api\V1\Http\Middleware;

use Illuminate\Http\Request;
use Closure;

/**
 * Class AppendIpAddress.
 *
 * @package Api\V1\Http\Middleware
 */
class AppendIpAddress
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request->merge(['ip_address' => $request->server->get('REMOTE_ADDR')]);

        return $next($request);
    }
}
