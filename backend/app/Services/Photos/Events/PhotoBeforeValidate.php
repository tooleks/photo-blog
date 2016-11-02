<?php

namespace App\Services\Photos\Events;

use App\Events\Event;
use App\Models\Photo;
use Illuminate\Contracts\Validation\Validator;

/**
 * Class PhotoBeforeValidate.
 * @property Photo photo
 * @property Validator validator
 * @property array attributes
 * @property string scenario
 * @package App\Services\Photos\Events
 */
class PhotoBeforeValidate extends Event
{
    /**
     * PhotoBeforeValidate constructor.
     *
     * @param Photo $photo
     * @param Validator $validator
     * @param array $attributes
     * @param string $scenario
     */
    public function __construct(Photo &$photo, Validator &$validator, array &$attributes, string &$scenario)
    {
        $this->photo = &$photo;
        $this->validator = &$validator;
        $this->attributes = &$attributes;
        $this->scenario = &$scenario;
    }
}
