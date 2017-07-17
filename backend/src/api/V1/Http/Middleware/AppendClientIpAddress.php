<?php

namespace Api\V1\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Class AppendClientIpAddress.
 *
 * @package Api\V1\Http\Middleware
 */
class AppendClientIpAddress
{
    /**
     * Possible server options from which client IP can be retrieved ordered by priority.
     *
     * @var array
     */
    private $clientIpServerOptions = [
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_FORWARDED',
        'HTTP_X_CLUSTER_CLIENT_IP',
        'HTTP_FORWARDED_FOR',
        'HTTP_FORWARDED',
        'REMOTE_ADDR',
    ];

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request->merge(['client_ip_address' => $this->resolveClientIpAddress($request)]);

        return $next($request);
    }

    /**
     * Resolve a client IP address from an incoming request.
     *
     * @param Request $request
     * @return string|null
     */
    protected function resolveClientIpAddress($request)
    {
        foreach ($this->clientIpServerOptions as $option) {
            if ($request->server->get($option)) {
                $ip = $request->server->get($option);
                break;
            }
        }

        return $ip ?? null;
    }
}
