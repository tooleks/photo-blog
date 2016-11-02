<?php

namespace App\Services\Users\Events;

use App\Events\Event;
use App\Models\User;
use Illuminate\Contracts\Validation\Validator;

/**
 * Class UserAfterValidate.
 * @property User user
 * @property Validator validator
 * @property array attributes
 * @property string scenario
 * @package App\Services\Users\Events
 */
class UserAfterValidate extends Event
{
    /**
     * UserAfterValidate constructor.
     *
     * @param User $user
     * @param Validator $validator
     * @param array $attributes
     * @param string $scenario
     */
    public function __construct(User &$user, Validator &$validator, array &$attributes, string &$scenario)
    {
        $this->user = &$user;
        $this->validator = &$validator;
        $this->attributes = &$attributes;
        $this->scenario = &$scenario;
    }
}
