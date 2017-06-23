<?php

namespace Api\V1\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\ThrottleRequests as IlluminateThrottleRequests;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class ThrottleRequests.
 *
 * @package Api\V1\Http\Middleware
 */
class ThrottleRequests extends IlluminateThrottleRequests
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param int $maxAttempts
     * @param float|int $decayMinutes
     * @return mixed
     */
    public function handle($request, Closure $next, $maxAttempts = 60, $decayMinutes = 1)
    {
        $key = $this->resolveRequestSignature($request);

        if ($this->limiter->tooManyAttempts($key, $maxAttempts, $decayMinutes)) {
            $response = $this->buildResponse($key, $maxAttempts);
            throw new HttpException(
                Response::HTTP_TOO_MANY_REQUESTS,
                Response::$statusTexts[Response::HTTP_TOO_MANY_REQUESTS] ?? null,
                null,
                $response->headers->all()
            );
        }

        $this->limiter->hit($key, $decayMinutes);

        $response = $next($request);

        return $this->addHeaders(
            $response, $maxAttempts,
            $this->calculateRemainingAttempts($key, $maxAttempts)
        );
    }
}
