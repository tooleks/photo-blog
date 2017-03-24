<?php

namespace Api\V1\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;

/**
 * Class ResourceController.
 *
 * @property Request request
 * @property Guard guard
 * @property string presenterClass
 * @package Api\V1\Http\Controllers
 */
abstract class ResourceController extends Controller
{
    /**
     * ResourceController constructor.
     *
     * @param Request $request
     * @param Guard $guard
     * @param string $presenterClass
     */
    public function __construct(Request $request, Guard $guard, string $presenterClass)
    {
        $this->request = $request;
        $this->guard = $guard;
        $this->presenterClass = $presenterClass;
    }

    /**
     * @inheritdoc
     */
    public function callAction($method, $parameters)
    {
        $response = parent::callAction($method, $parameters);

        // If the response is an instance of a response class, return the response "as it".
        if ($response instanceof Response) {
            return $response;
        }

        // Otherwise, present the response data with the presenter class.
        return new Response($this->present($response), $this->getStatusCode());
    }

    /**
     * Present a response.
     *
     * @param mixed $response
     * @return mixed
     */
    protected function present($response)
    {
        // If the response is an instance of a paginator class,
        // present each item in the collection with a presenter class via present macros.
        if ($response instanceof AbstractPaginator) {
            $items = $response->getCollection()->present($this->presenterClass);
            return $response->setCollection($items);
        }

        // If the response is an instance of a collection,
        // present each item in the collection with a presenter class via present macros.
        if ($response instanceof Collection) {
            return $response->present($this->presenterClass);
        }

        // If the response is an object or an array, present it with a presenter class.
        if (is_object($response) || is_array($response)) {
            return new $this->presenterClass($response);
        }

        // Otherwise, return the response "as it".
        return $response;
    }

    /**
     * Get status code for request method.
     *
     * @return int
     */
    protected function getStatusCode()
    {
        switch ($this->request->getMethod()) {
            case Request::METHOD_POST:
                return Response::HTTP_CREATED;
            case Request::METHOD_DELETE:
                return Response::HTTP_NO_CONTENT;
            default:
                return Response::HTTP_OK;
        }
    }
}
