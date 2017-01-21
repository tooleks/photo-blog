<?php

namespace Api\V1\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class JsonApi
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
        if (!$request->wantsJson()) {
            throw new BadRequestHttpException(trans('errors.http.400'));
        }

        $response = $next($request);

        return $this->buildResponse($response);
    }

    /**
     * Create a JSON API response.
     *
     * @param Response $response
     * @return Response
     */
    protected function buildResponse(Response $response)
    {
        if ($response->getStatusCode() === 200) {
            $response = response()->json([
                'status' => true,
                'data' => $response->getOriginalContent(),
            ], $response->getStatusCode(), $response->headers->all());
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
