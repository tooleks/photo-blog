<?php

namespace Api\V1\Http\Controllers;

use Api\V1\Core\Resource\Contracts\Resource;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/**
 * Class ResourceController.
 *
 * @property Request request
 * @property Guard guard
 * @property Resource resource
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
     */
    public function __construct(Request $request, Guard $guard, Resource $resource)
    {
        $this->request = $request;
        $this->guard = $guard;
        $this->resource = $resource;
    }

    /**
     * Get a resource by unique ID.
     *
     * @param mixed $id
     * @return mixed
     */
    public function getById($id)
    {
        return $this->resource->getById($id);
    }

    /**
     * Get resources.
     *
     * @return mixed
     */
    public function get()
    {
        return $this->resource->get(
            $this->request->query->get('take', 10),
            $this->request->query->get('skip', 0),
            $this->request->all()
        );
    }

    /**
     * Create a resource.
     *
     * @return mixed
     */
    public function create()
    {
        return $this->resource->create($this->request->all());
    }

    /**
     * Update a resource by unique ID.
     *
     * @param mixed $id
     * @return mixed
     */
    public function updateById($id)
    {
        return $this->resource->updateById($id, $this->request->all());
    }

    /**
     * Delete a resource by unique ID.
     *
     * @param mixed $id
     * @return mixed
     */
    public function deleteById($id)
    {
        return $this->resource->deleteById($id);
    }
}
