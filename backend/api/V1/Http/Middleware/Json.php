<?php

namespace Api\V1\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class Json
 * @package App\Http\Middleware
 */
class Json
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (!$request->wantsJson()) {
            throw new BadRequestHttpException(trans('errors.http.400'));
        }

        /** @var Response $response */

        $response = $next($request);

        $response->withHeaders([
            'Content-type' => 'application/json',
        ]);

        if ($response->getStatusCode() === 200) {
            $response = response()->json([
                'status' => true,
                'data' => $response->getOriginalContent(),
            ], $response->getStatusCode(), $response->headers->all());
        }

        return $response;
    }
}
