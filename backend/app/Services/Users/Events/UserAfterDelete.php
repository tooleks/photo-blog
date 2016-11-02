<?php

namespace App\Services\Users\Events;

use App\Events\Event;
use App\Models\User;

/**
 * Class UserAfterDelete.
 * @property User $user
 * @property int count
 * @package App\Services\Users\Events
 */
class UserAfterDelete extends Event
{
    /**
     * UserAfterDelete constructor.
     *
     * @param User $user
     * @param int $count
     */
    public function __construct(User &$user, int &$count)
    {
        $this->user = &$user;
        $this->count = &$count;
    }
}
