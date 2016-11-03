<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class Api
 * @package App\Http\Middleware
 */
class Api
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
