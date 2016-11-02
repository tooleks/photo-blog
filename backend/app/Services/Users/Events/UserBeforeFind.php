<?php

namespace App\Services\Users\Events;

use App\Events\Event;
use App\Models\User;

/**
 * Class UserBeforeFind.
 * @property User user
 * @property array parameters
 * @property string scenario
 * @package App\Services\Users\Events
 */
class UserBeforeFind extends Event
{
    /**
     * UserBeforeFind constructor.
     *
     * @param User $user
     * @param array $parameters
     * @param string $scenario
     */
    public function __construct(User &$user, array &$parameters = [], &$scenario = null)
    {
        $this->user = &$user;
        $this->parameters = &$parameters;
        $this->scenario = &$scenario;
    }
}
