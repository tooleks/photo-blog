<?php

namespace Api\V1\Http\Middleware;

use Api\V1\Http\Proxy\Contracts\OAuthProxy;
use Closure;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class CookieOAuthTokenRefresher.
 *
 * @package Api\V1\Http\Middleware
 */
class CookieOAuthTokenRefresher
{
    /**
     * TODO: Implement more intelligent way to track the middleware hitting.
     *
     * @var bool
     */
    protected static $hit = false;

    /**
     * @var Application
     */
    protected $app;

    /**
     * @var OAuthProxy
     */
    protected $oAuthProxy;

    /**
     * CookieOAuthTokenRefresher constructor.
     *
     * @param Application $app
     * @param OAuthProxy $oAuthProxy
     */
    public function __construct(Application $app, OAuthProxy $oAuthProxy)
    {
        $this->app = $app;
        $this->oAuthProxy = $oAuthProxy;
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
        $response = $next($request);

        if ($response->getStatusCode() === Response::HTTP_UNAUTHORIZED && !static::$hit) {
            $response = $this->retryRequestWithRefreshedToken($request, $response);
            static::$hit = true;
        }

        return $response;
    }

    /**
     * Retry the request with the refreshed token.
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    protected function retryRequestWithRefreshedToken($request, $response)
    {
        $authorizationResponse = $this->oAuthProxy->requestTokenByRefreshToken(
            env('OAUTH_CLIENT_ID'),
            $request->cookie('refresh_token')
        );

        if (!$authorizationResponse->isSuccessful()) {
            return $response;
        }

        foreach ($authorizationResponse->headers->getCookies() as $cookie) {
            $request->cookies->set($cookie->getName(), $cookie->getValue());
        }

        $retriedResponse = $this->app->handle($request);

        foreach ($authorizationResponse->headers->getCookies() as $cookie) {
            $retriedResponse->headers->setCookie($cookie);
        }

        return $retriedResponse;
    }
}
