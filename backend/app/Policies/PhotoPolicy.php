<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Photo;
use Illuminate\Auth\Access\HandlesAuthorization;

class PhotoPolicy
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
        return false;
    }

    /**
     * Determine whether the user can update the photo.
     *
     * @param User $authUser
     * @param Photo $photo
     * @return bool
     */
    public function update(User $authUser, Photo $photo)
    {
        if ($authUser->id === $photo->user_id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the photo.
     *
     * @param User $authUser
     * @param Photo $photo
     * @return bool
     */
    public function delete(User $authUser, Photo $photo)
    {
        if ($authUser->id === $photo->user_id) {
            return true;
        }

        return false;
    }
}
