<?php

namespace Api\V1\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class CookieOAuthAuthorizer.
 *
 * @package Api\V1\Http\Middleware
 */
class CookieOAuthAuthorizer
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle($request, Closure $next)
    {
        $this->addAuthorizationHeader($request);

        $response = $next($request);

        return $response;
    }

    /**
     * Add the authorization header to the given request from the cookies.
     *
     * @param Request $request
     * @return void
     */
    protected function addAuthorizationHeader($request): void
    {
        if (
            $request->hasCookie('token_type') && $request->hasCookie('expires_in') &&
            $request->hasCookie('access_token') && $request->hasCookie('refresh_token')
        ) {
            $request->headers->set('Authorization', $request->cookie('token_type') . ' ' . $request->cookie('access_token'));
        }
    }
}
