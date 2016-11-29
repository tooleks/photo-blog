<?php

namespace Api\V1\Core\Resource\Contracts;

/**
 * Interface Resource
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
     * Get resources collection by parameters.
     *
     * @param int $take
     * @param int $skip
     * @param array $parameters
     * @return mixed
     */
    public function getCollection($take, $skip, array $parameters);

    /**
     * Create a resource.
     *
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes);

    /**
     * Update a resource.
     *
     * @param mixed $resource
     * @param array $attributes
     * @return mixed
     */
    public function update($resource, array $attributes);

    /**
     * Delete a resource
     *
     * @param mixed $resource
     * @return int
     */
    public function delete($resource);
}
