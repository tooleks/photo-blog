<?php

namespace Api\V1\Policies;

use App\Models\DB\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class UserPolicy.
 *
 * @package Api\V1\Policies
 */
class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Before policy filter.
     *
     * @param User $authUser
     * @param $ability
     * @return bool
     */
    public function before(User $authUser, $ability)
    {
        if ($authUser->isAdministrator()) {
            return true;
        }
    }

    /**
     * Determine whether the user can create photos.
     *
     * @param User $authUser
     * @return bool
     */
    public function create(User $authUser)
    {
        return true;
    }

    /**
     * Determine whether the user can update the photo.
     *
     * @param User $authUser
     * @param User $user
     * @return bool
     */
    public function get(User $authUser, User $user)
    {
        if ($authUser->id === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the photo.
     *
     * @param User $authUser
     * @param User $user
     * @return bool
     */
    public function update(User $authUser, User $user)
    {
        if ($authUser->id === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the photo.
     *
     * @param User $authUser
     * @param User $user
     * @return bool
     */
    public function delete(User $authUser, User $user)
    {
        if ($authUser->id === $user->id) {
            return true;
        }

        return false;
    }
}
