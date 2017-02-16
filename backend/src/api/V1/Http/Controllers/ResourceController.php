<?php

namespace Api\V1\Http\Controllers;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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

        return new Response($this->present($response), $this->getStatusCode($method));
    }

    /**
     * Present a resource.
     *
     * @param mixed $resource
     * @return mixed
     */
    protected function present($resource)
    {
        // If resource is the collection, present each item with a presenter class via present macros.
        if ($resource instanceof Collection) {
            return $resource->present($this->presenterClass);
        }

        // If resource is an object or an array, present it with a presenter class.
        if (is_object($resource) || is_array($resource)) {
            return new $this->presenterClass($resource);
        }

        // Otherwise, return resource "as it".
        return $resource;
    }

    /**
     * Get status code for method.
     *
     * @param string $method
     * @return int
     */
    protected function getStatusCode(string $method)
    {
        switch ($method) {
            case 'create':
                return Response::HTTP_CREATED;
            default:
                return Response::HTTP_OK;
        }
    }
}
