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
        if ($authUser->toEntity()->isAdministrator()) {
            return true;
        }

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
        return $this->hasAccessToResource($authUser, $resource);
    }

    /**
     * Determine if an authenticated user has access to a resource.
     *
     * @param User $authUser
     * @param $resource
     * @return bool
     */
    private function hasAccessToResource(User $authUser, $resource): bool
    {
        if ($authUser->toEntity()->isAdministrator()) {
            return true;
        }

        if ($resource instanceof User && $authUser->id === optional($resource)->id) {
            return true;
        }

        if ($authUser->id === optional($resource)->created_by_user_id) {
            return true;
        }

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
        return $this->hasAccessToResource($authUser, $resource);
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
        return $this->hasAccessToResource($authUser, $resource);
    }
}
