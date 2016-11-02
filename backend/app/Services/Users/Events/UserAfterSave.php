<?php

namespace App\Services\Users\Events;

use App\Events\Event;
use App\Models\User;

/**
 * Class UserAfterSave.
 * @property User user
 * @property array attributes
 * @property string scenario
 * @package App\Services\Users\Events
 */
class UserAfterSave extends Event
{
    /**
     * UserAfterSave constructor.
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
