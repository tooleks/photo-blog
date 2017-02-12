<?php

namespace Api\V1\Core\Resource\Contracts;

/**
 * Interface Resource.
 *
 * @package Api\V1\Core\Resource\Contracts
 */
interface Resource
{
    /**
     * Get a resource by unique ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getById($id);

    /**
     * Get resources by parameters.
     *
     * @param int $take
     * @param int $skip
     * @param array $parameters
     * @return mixed
     */
    public function get($take, $skip, array $parameters);

    /**
     * Create a resource.
     *
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes);

    /**
     * Update a resource by unique ID.
     *
     * @param mixed $resource
     * @param array $attributes
     * @return mixed
     */
    public function updateById($resource, array $attributes);

    /**
     * Delete a resource by unique ID.
     *
     * @param mixed $resource
     * @return int
     */
    public function deleteById($resource);
}
