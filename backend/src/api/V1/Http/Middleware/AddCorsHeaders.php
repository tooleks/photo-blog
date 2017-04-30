<?php

namespace Api\V1\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AddCorsHeaders.
 *
 * @package Api\V1\Http\Middleware
 */
class AddCorsHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next)
    {
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
