<?php

namespace Api\V1\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AllowCorsRequests
 *
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

        return $this->addHeaders($response);
    }

    /**
     * Add the CORS header information to the given response.
     *
     * @param Response $response
     * @return Response
     */
    protected function addHeaders(Response $response)
    {
        $response->headers->add([
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'POST, GET, OPTIONS, PUT, DELETE',
            'Access-Control-Allow-Headers' => 'Content-Type, Accept, Authorization, X-Requested-With',
        ]);

        return $response;
    }
}
