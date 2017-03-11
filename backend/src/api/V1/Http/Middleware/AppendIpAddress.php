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
        $request->merge(['ip_address' => $this->resolveClientIpAddress($request) ?? 'N/A']);

        return $next($request);
    }

    /**
     * Resolve client IP address from request.
     *
     * @param $request
     * @return null|string
     */
    private function resolveClientIpAddress($request)
    {
        $ip = null;

        if ($request->server->has('HTTP_CLIENT_IP')) {
            $ip = $request->server->get('HTTP_CLIENT_IP');
        } elseif ($request->server->has('HTTP_X_FORWARDED_FOR')) {
            $ip = $request->server->get('HTTP_X_FORWARDED_FOR');
        } elseif ($request->server->has('HTTP_X_FORWARDED')) {
            $ip = $request->server->get('HTTP_X_FORWARDED');
        } elseif ($request->server->has('HTTP_X_CLUSTER_CLIENT_IP')) {
            $ip = $request->server->get('HTTP_X_CLUSTER_CLIENT_IP');
        } elseif ($request->server->has('HTTP_FORWARDED_FOR')) {
            $ip = $request->server->get('HTTP_FORWARDED_FOR');
        } elseif ($request->server->has('HTTP_FORWARDED')) {
            $ip = $request->server->get('HTTP_FORWARDED');
        } elseif ($request->server->has('REMOTE_ADDR')) {
            $ip = $request->server->get('REMOTE_ADDR');
        }

        return $ip;
    }
}
