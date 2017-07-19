<?php

namespace Api\V1\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use InvalidArgumentException;
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

        $this->encodeContent($response);
        $this->addHeaders($response);
        $this->setStatusCode($request, $response);

        return $response;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     */
    private function setStatusCode($request, $response): void
    {
        if ($response->isSuccessful()) {
            if ($request->getMethod() === Request::METHOD_POST)
                $response->setStatusCode(Response::HTTP_CREATED);
            elseif ($request->getMethod() === Request::METHOD_DELETE)
                $response->setStatusCode(Response::HTTP_NO_CONTENT);
        }
    }

    /**
     * @param Response $response
     * @return void
     */
    private function addHeaders($response): void
    {
        $response->headers->set('Content-Type', 'application/json');
    }

    /**
     * @param Response $response
     * @return void
     */
    private function encodeContent($response): void
    {
        $isValidJson = function (string $json): bool {
            try {
                return (bool) \GuzzleHttp\json_decode($json);
            } catch (InvalidArgumentException $e) {
                return false;
            }
        };

        if (!$isValidJson($response->getContent())) {
            $response->setContent(
                \GuzzleHttp\json_encode($response->getContent())
            );
        }
    }
}
