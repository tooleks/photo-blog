<?php

namespace Api\V1\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
        if (!$request->wantsJson()) {
            throw new HttpException(Response::HTTP_NOT_ACCEPTABLE);
        }

        $response = $next($request);

        return $response;
    }
}
