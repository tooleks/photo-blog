<?php

namespace Api\V1\Http\Middleware;

use Closure;
use Illuminate\Contracts\Container\Container;
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
     * The container implementation.
     *
     * @var Container
     */
    private $container;

    /**
     * PresentResponses constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string $presenterClass
     * @return Response
     */
    public function handle($request, Closure $next, string $presenterClass)
    {
        $response = $next($request);

        if ($response->isSuccessful()) {
            $response->setContent(
                $this->present($response->getOriginalContent(), $presenterClass)
            );
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
    private function present($data, string $presenterClass)
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
            return $this->container
                ->make($presenterClass)
                ->setWrappedModel($data);
        }

        // Otherwise, return the data "as it".
        return $data;
    }
}
