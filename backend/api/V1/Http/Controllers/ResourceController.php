<?php

namespace Api\V1\Http\Controllers;

use Api\V1\Core\Resource\Contracts\Resource;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;

/**
 * Class ResourceController.
 *
 * @property Request request
 * @property Guard guard
 * @property Resource resource
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
     * @param Resource $resource
     * @param string $presenterClass
     */
    public function __construct(Request $request, Guard $guard, Resource $resource, string $presenterClass)
    {
        $this->request = $request;
        $this->guard = $guard;
        $this->resource = $resource;
        $this->presenterClass = $presenterClass;
    }

    /**
     * Present a resource.
     *
     * @param mixed $value
     * @return mixed
     */
    protected function present($value)
    {
        if ($value instanceof Collection) {
            return $value->map(function ($item) {
                return new $this->presenterClass($item);
            });
        }

        return new $this->presenterClass($value);
    }

    /**
     * Get a resource.
     *
     * @param mixed $resource
     * @return mixed
     */
    public function get($resource)
    {
        return $this->present($resource);
    }

    /**
     * Get resources collection.
     *
     * @return mixed
     */
    public function getCollection()
    {
        $resources = $this->resource->getCollection(
            $this->request->query->get('take', 10),
            $this->request->query->get('skip', 0),
            $this->request->all()
        );

        return $this->present($resources);
    }

    /**
     * Create a resource.
     *
     * @return mixed
     */
    public function create()
    {
        $resource = $this->resource->create($this->request->all());

        return $this->present($resource);
    }

    /**
     * Update a resource.
     *
     * @param mixed $resource
     * @return mixed
     */
    public function update($resource)
    {
        $resource = $this->resource->update($resource, $this->request->all());

        return $this->present($resource);
    }

    /**
     * Delete a resource.
     *
     * @param mixed $resource
     * @return mixed
     */
    public function delete($resource)
    {
        return $this->resource->delete($resource);
    }
}
