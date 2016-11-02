<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class UserPolicy
 * @package App\Policies
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
     * Determine whether the user can view users.
     *
     * @param User $authUser
     * @param User $user
     * @return bool
     */
    public function view(User $authUser, User $user)
    {
        if ($authUser->id === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create users.
     *
     * @param User $authUser
     * @return bool
     */
    public function create(User $authUser)
    {
        return false;
    }

    /**
     * Determine whether the user can update the user.
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
     * Determine whether the user can delete the user.
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
