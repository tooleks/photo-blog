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
        $this->convertAuthorizationCookieToHeader($request);

        $response = $next($request);

        return $response;
    }

    /**
     * Convert an authorization cookie to a header for a request.
     *
     * @param Request $request
     * @return void
     */
    private function convertAuthorizationCookieToHeader($request): void
    {
        if (
            $request->hasCookie('token_type') &&
            $request->hasCookie('expires_in') &&
            $request->hasCookie('access_token') &&
            $request->hasCookie('refresh_token')
        ) {
            $request->headers->set(
                'Authorization',
                $request->cookie('token_type') . ' ' . $request->cookie('access_token')
            );
        }
    }
}
