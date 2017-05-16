<?php

namespace Api\V1\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Contracts\Cache\Factory as CacheManager;

/**
 * Class CacheResponses.
 *
 * @property CacheManager cacheManager
 * @package Api\V1\Http\Middleware
 */
class CacheResponses
{
    /**
     * CacheResponses constructor.
     *
     * @param CacheManager $cacheManager
     */
    public function __construct(CacheManager $cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param float $expiresAfterMinutes
     * @param bool $withAuth
     * @return Response
     */
    public function handle(Request $request, Closure $next, float $expiresAfterMinutes, bool $withAuth = false)
    {
        $key = sprintf('response:%s', $this->getRequestFingerprint($request, $withAuth));

        $response = $this->cacheManager->remember($key, $expiresAfterMinutes, function () use ($request, $next) {
            return $next($request);
        });

        return $response;
    }

    /**
     * Get request fingerprint.
     *
     * @param Request $request
     * @param bool $includeAuth
     * @return string
     */
    protected function getRequestFingerprint(Request $request, bool $includeAuth): string
    {
        $data = [
            $this->fetchSerializedRouteUri($request),
            $this->fetchSerializedRouteParameters($request),
            $this->fetchSerializedQueryParameters($request),
        ];

        if ($includeAuth) {
            $data[] = $this->fetchSerializedAuth($request);
        }

        return sha1(implode('|', $data));
    }

    /**
     * Fetch serialized auth.
     *
     * @param Request $request
     * @return string
     */
    protected function fetchSerializedAuth(Request $request): string
    {
        // Get authenticated user or null.
        $user = $request->getUserResolver()();

        return serialize($user->id ?? null);
    }

    /**
     * Fetch serialized route URI.
     *
     * @param Request $request
     * @return string
     */
    protected function fetchSerializedRouteUri(Request $request): string
    {
        $routeUri = $request->route()->uri();

        return serialize($routeUri);
    }

    /**
     * Fetch serialized route parameters.
     *
     * @param Request $request
     * @return string
     */
    protected function fetchSerializedRouteParameters(Request $request): string
    {
        $routeParameters = $request->route()->parameters();

        ksort($routeParameters);

        return serialize($routeParameters);
    }

    /**
     * Fetch serialized query parameters.
     *
     * @param Request $request
     * @return string
     */
    protected function fetchSerializedQueryParameters(Request $request): string
    {
        $queryParameters = $request->query();

        ksort($queryParameters);

        return serialize($queryParameters);
    }
}
