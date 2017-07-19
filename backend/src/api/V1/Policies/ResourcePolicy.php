<?php

namespace Api\V1\Policies;

use App\Models\User;

/**
 * Class ResourcePolicy.
 *
 * @package Api\V1\Policies
 */
class ResourcePolicy
{
    /**
     * Create a resource.
     *
     * @param User $authUser
     * @param string $resourceClass
     * @return bool
     */
    public function create(User $authUser, string $resourceClass)
    {
        // If authenticated user is administrator allow access to resource.
        if ($authUser->isAdministrator()) {
            return true;
        }

        // Otherwise deny access to resource.
        return false;
    }

    /**
     * Get a resource.
     *
     * @param User $authUser
     * @param mixed $resource
     * @return bool
     */
    public function get(User $authUser, $resource)
    {
        // If authenticated user is administrator allow access to resource.
        if ($authUser->isAdministrator()) {
            return true;
        }

        // If authenticated user is resource owner allow access to resource.
        if ($authUser->id == $resource->user_id) {
            return true;
        }

        // Otherwise deny access to resource.
        return false;
    }

    /**
     * Update a resource.
     *
     * @param User $authUser
     * @param mixed $resource
     * @return bool
     */
    public function update(User $authUser, $resource)
    {
        // If authenticated user is administrator allow access to resource.
        if ($authUser->isAdministrator()) {
            return true;
        }

        // If authenticated user is resource owner allow access to resource.
        if ($authUser->id == $resource->user_id) {
            return true;
        }

        // Otherwise deny access to resource.
        return false;
    }

    /**
     * Delete a resource.
     *
     * @param User $authUser
     * @param mixed $resource
     * @return bool
     */
    public function delete(User $authUser, $resource)
    {
        // If authenticated user is administrator allow access to resource.
        if ($authUser->isAdministrator()) {
            return true;
        }

        // If authenticated user is resource owner allow access to resource.
        if ($authUser->id == $resource->user_id) {
            return true;
        }

        // Otherwise deny access to resource.
        return false;
    }
}
