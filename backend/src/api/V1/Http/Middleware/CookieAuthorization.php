<?php

namespace Api\V1\Http\Middleware;

use Api\V1\Http\Proxy\Contracts\AuthorizationProxy;
use Closure;
use Illuminate\Http\Request;

/**
 * Class CookieAuthorization.
 *
 * @package Api\V1\Http\Middleware
 */
class CookieAuthorization
{
    /**
     * @var AuthorizationProxy
     */
    protected $authorizationProxy;

    /**
     * CookieAuthorization constructor.
     *
     * @param AuthorizationProxy $authorizationProxy
     */
    public function __construct(AuthorizationProxy $authorizationProxy)
    {
        $this->authorizationProxy = $authorizationProxy;
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
        $request = $this->addHeaders($request);

        $response = $next($request);

        return $response;
    }

    /**
     * Add the authorization header to the given request.
     *
     * @param Request $request
     * @return Request
     */
    public function addHeaders($request)
    {
        if (!$this->hasAuthorizationHeader($request) && $this->hasAuthorizationCookies($request)) {
            $this->addAuthorizationHeader($request);
        }

        return $request;
    }

    /**
     * Check if the requests has the authorization header.
     *
     * @param Request $request
     * @return bool
     */
    protected function hasAuthorizationHeader($request): bool
    {
        return $request->headers->has('Authorization');
    }

    /**
     * Check if the requests has authorization cookies.
     *
     * @param Request $request
     * @return bool
     */
    protected function hasAuthorizationCookies($request): bool
    {
        return $request->hasCookie('token_type') &&
            $request->hasCookie('expires_in') &&
            $request->hasCookie('access_token') &&
            $request->hasCookie('refresh_token');
    }

    /**
     * Add the authorization header to the request.
     *
     * @param Request $request
     * @return void
     */
    protected function addAuthorizationHeader($request): void
    {
        $request->headers->add([
            'Authorization' => "{$request->cookie('token_type')} {$request->cookie('access_token')}",
        ]);
    }
}
