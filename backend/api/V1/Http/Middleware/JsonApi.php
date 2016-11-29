<?php

namespace Api\V1\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class JsonApi
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
        $this->handleRequest($request);

        $response = $next($request);

        $this->handleResponse($response);

        return $response;
    }

    /**
     * @param Request $request
     */
    protected function handleRequest(&$request)
    {
        if (!$request->wantsJson()) {
            throw new BadRequestHttpException(trans('errors.http.400'));
        }
    }

    /**
     * @param Response $response
     */
    protected function handleResponse(&$response)
    {
        $response->header('Content-Type', 'application/json');

        if ($response->getStatusCode() === 200) {
            $response = response()->json([
                'status' => true,
                'data' => $response->getOriginalContent(),
            ], $response->getStatusCode(), $response->headers->all());
        }
    }
}
