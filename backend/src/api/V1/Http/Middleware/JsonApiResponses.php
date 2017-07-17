<?php

namespace Api\V1\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class JsonApiResponses.
 *
 * @package Api\V1\Http\Middleware
 */
class JsonApiResponses
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
        $this->assertRequest($request);

        $response = $next($request);

        return $this->buildResponse($response);
    }

    /**
     * Assert a request.
     *
     * @param Request $request
     */
    protected function assertRequest($request)
    {
        if (!$request->wantsJson()) {
            throw new HttpException(Response::HTTP_NOT_ACCEPTABLE);
        }
    }

    /**
     * Create a response.
     *
     * @param Response $response
     * @return Response
     */
    protected function buildResponse($response)
    {
        if (method_exists($response, 'getOriginalContent') && $response->isSuccessful()) {
            $statusCode = $response->getStatusCode();
            $headers = $response->headers->all();
            $content = $response->getOriginalContent();
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
    protected function addHeaders($response)
    {
        $response->headers->add(['Content-Type' => 'application/json']);

        return $response;
    }
}
