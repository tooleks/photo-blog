<?php

namespace App\Services\Users\Events;

use App\Events\Event;
use App\Models\User;

/**
 * Class UserBeforeDelete.
 * @property User user
 * @package App\Services\Users\Events
 */
class UserBeforeDelete extends Event
{
    /**
     * UserBeforeDelete constructor.
     *
     * @param User $user
     */
    public function __construct(User &$user)
    {
        $this->user = &$user;
    }
}
