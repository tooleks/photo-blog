<?php

namespace Api\V1\Policies;

use App\Models\DB\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class ResourcePolicy.
 *
 * @package Api\V1\Policies
 */
class ResourcePolicy
{
    /**
     * Create resource gate.
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
     * Get resource gate.
     *
     * @param User $authUser
     * @param string $resourceClass
     * @param mixed $resourceId
     * @return bool
     */
    public function get(User $authUser, string $resourceClass, $resourceId)
    {
        // If authenticated user is administrator allow access to resource.
        if ($authUser->isAdministrator()) {
            return true;
        }

        $resource = $resourceClass::select('user_id')->whereId($resourceId)->first();

        if (is_null($resource)) {
            throw new ModelNotFoundException(sprintf('%s not found.', class_basename($resourceClass)));
        }

        // If authenticated user is the resource owner allow access to resource.
        if ($authUser->id == $resource->user_id) {
            return true;
        }

        // Otherwise deny access to resource.
        return false;
    }

    /**
     * Update resource gate.
     *
     * @param User $authUser
     * @param string $resourceClass
     * @param mixed $resourceId
     * @return bool
     */
    public function update(User $authUser, string $resourceClass, $resourceId)
    {
        // If authenticated user is administrator allow access to resource.
        if ($authUser->isAdministrator()) {
            return true;
        }

        $resource = $resourceClass::select('user_id')->whereId($resourceId)->first();

        if (is_null($resource)) {
            throw new ModelNotFoundException(sprintf('%s not found.', class_basename($resourceClass)));
        }

        // If authenticated user is the resource owner allow access to resource.
        if ($authUser->id == $resource->user_id) {
            return true;
        }

        // Otherwise deny access to resource.
        return false;
    }

    /**
     * Delete resource.
     *
     * @param User $authUser
     * @param string $resourceClass
     * @param mixed $resourceId
     * @return bool
     */
    public function delete(User $authUser, string $resourceClass, $resourceId)
    {
        // If authenticated user is administrator allow access to resource.
        if ($authUser->isAdministrator()) {
            return true;
        }

        $resource = $resourceClass::select('user_id')->whereId($resourceId)->first();

        if (is_null($resource)) {
            throw new ModelNotFoundException(sprintf('%s not found.', class_basename($resourceClass)));
        }

        // If authenticated user is the resource owner allow access to resource.
        if ($authUser->id == $resource->user_id) {
            return true;
        }

        // Otherwise deny access to resource.
        return false;
    }
}
