<?php

namespace Api\V1\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class JsonApi.
 *
 * @package Api\V1\Http\Middleware
 */
class JsonApi
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
        $this->assertRequest($request);

        $response = $next($request);

        return $this->buildResponse($response);
    }

    /**
     * Assert a request.
     *
     * @param Request $request
     */
    protected function assertRequest(Request $request)
    {
        if (!$request->wantsJson()) {
            throw new HttpException(406, trans('errors.http.406'));
        }
    }

    /**
     * Create a response.
     *
     * @param Response $response
     * @return Response
     */
    protected function buildResponse(Response $response)
    {
        $statusCode = $response->getStatusCode();
        $headers = $response->headers->all();
        $content = $response->getOriginalContent();

        if ($statusCode === Response::HTTP_OK || $statusCode === Response::HTTP_CREATED) {
            $response = response()->json($content, $statusCode, $headers);
        }

        return $this->addHeaders($response);
    }

    /**
     * Add the JSON API header information to the given response.
     *
     * @param Response $response
     * @return Response
     */
    protected function addHeaders(Response $response)
    {
        $response->headers->add(['Content-Type' => 'application/json']);

        return $response;
    }
}
