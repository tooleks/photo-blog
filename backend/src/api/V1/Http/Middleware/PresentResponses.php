<?php

namespace Api\V1\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Collection;

/**
 * Class PresentResponses.
 *
 * @package Api\V1\Http\Middleware
 */
class PresentResponses
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string $presenterClass
     * @return Response
     */
    public function handle(Request $request, Closure $next, string $presenterClass)
    {
        $response = $next($request);

        if ($response->isSuccessful()) {
            $content = $this->present($response->getOriginalContent(), $presenterClass);
            $statusCode = $this->getStatusCode($request);
            $response->setContent($content);
            $response->setStatusCode($statusCode);
        }

        return $response;
    }

    /**
     * Present a data.
     *
     * @param mixed $data
     * @param $presenterClass
     * @return mixed
     */
    protected function present($data, string $presenterClass)
    {
        // If the data is an instance of a paginator class,
        // present each item in the collection with a presenter class via present macros.
        if ($data instanceof AbstractPaginator) {
            $items = $data->getCollection()->present($presenterClass);
            return $data->setCollection($items);
        }

        // If the data is an instance of a collection,
        // present each item in the collection with a presenter class via present macros.
        if ($data instanceof Collection) {
            return $data->present($presenterClass);
        }

        // If the data is an object or an array, present it with a presenter class.
        if (is_object($data) || is_array($data)) {
            return new $presenterClass($data);
        }

        // Otherwise, return the data "as it".
        return $data;
    }

    /**
     * Get status code for request method.
     *
     * @param Request $request
     * @return int
     */
    protected function getStatusCode($request)
    {
        switch ($request->getMethod()) {
            case Request::METHOD_POST:
                return Response::HTTP_CREATED;
            case Request::METHOD_DELETE:
                return Response::HTTP_NO_CONTENT;
            default:
                return Response::HTTP_OK;
        }
    }
}
