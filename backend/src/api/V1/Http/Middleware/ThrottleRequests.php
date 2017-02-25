<?php

namespace Api\V1\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\ThrottleRequests as CoreThrottleRequests;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class ThrottleRequests.
 *
 * @package Api\V1\Http\Middleware
 */
class ThrottleRequests extends CoreThrottleRequests
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
            return $this->throwResponse($key, $maxAttempts);
        }

        $this->limiter->hit($key, $decayMinutes);

        $response = $next($request);

        $headers = $this->buildHeaders($maxAttempts, $this->calculateRemainingAttempts($key, $maxAttempts));

        $response->headers->add($headers);

        return $response;
    }

    /**
     * Build the limit header information.
     *
     * @param int $maxAttempts
     * @param int $remainingAttempts
     * @param int|null $retryAfter
     * @return Response
     */
    protected function buildHeaders($maxAttempts, $remainingAttempts, $retryAfter = null)
    {
        $headers = [
            'X-RateLimit-Limit' => $maxAttempts,
            'X-RateLimit-Remaining' => $remainingAttempts,
        ];

        if (!is_null($retryAfter)) {
            $headers['Retry-After'] = $retryAfter;
            $headers['X-RateLimit-Reset'] = Carbon::now()->getTimestamp() + $retryAfter;
        }

        return $headers;
    }

    /**
     * Throw a 'too many attempts' exception.
     *
     * @param  string $key
     * @param  int $maxAttempts
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function throwResponse($key, $maxAttempts)
    {
        $retryAfter = $this->limiter->availableIn($key);

        $remainingAttempts = $this->calculateRemainingAttempts($key, $maxAttempts, $retryAfter);

        $headers = $this->buildHeaders($maxAttempts, $remainingAttempts, $retryAfter);

        throw new HttpException(429, trans('errors.http.429'), null, $headers);
    }
}
