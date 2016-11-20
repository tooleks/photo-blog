<?php

namespace Api\V1\Policies;

use App\Models\DB\User;
use Api\V1\Models\Presenters\PhotoPresenter;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class PhotoPolicy
 * @package Api\V1\Policies
 */
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
        return true;
    }

    /**
     * Determine whether the user can update the photo.
     *
     * @param User $authUser
     * @param PhotoPresenter $photoPresenter
     * @return bool
     */
    public function update(User $authUser, PhotoPresenter $photoPresenter)
    {
        if ($authUser->id === $photoPresenter->user_id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the photo.
     *
     * @param User $authUser
     * @param PhotoPresenter $photoPresenter
     * @return bool
     */
    public function delete(User $authUser, PhotoPresenter $photoPresenter)
    {
        if ($authUser->id === $photoPresenter->user_id) {
            return true;
        }

        return false;
    }
}
