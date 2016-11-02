<?php

namespace App\Services\Users\Events;

use App\Events\Event;
use App\Models\User;

/**
 * Class UserBeforeSave
 * @property User user
 * @property array attributes
 * @property string scenario
 * @package App\Services\Users\Events
 */
class UserBeforeSave extends Event
{
    /**
     * UserBeforeSave constructor.
     *
     * @param User $user
     * @param array $attributes
     * @param string $scenario
     */
    public function __construct(User &$user, array &$attributes, string &$scenario)
    {
        $this->user = &$user;
        $this->attributes = &$attributes;
        $this->scenario = &$scenario;
    }
}
