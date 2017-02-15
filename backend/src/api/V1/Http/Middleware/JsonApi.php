<?php

namespace Api\V1\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

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
        $statusCode = $response->getStatusCode();
        $headers = $response->headers->all();
        $content = $response->getOriginalContent();

        if ($statusCode === Response::HTTP_OK || $statusCode === Response::HTTP_CREATED) {
            $response = response()->json([
                'status' => true,
                'data' => $content,
            ], $statusCode, $headers);
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
