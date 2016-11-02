<?php

namespace App\Services\Photos\Events;

use App\Events\Event;
use App\Models\Photo;

/**
 * Class PhotoBeforeSave
 * @property Photo photo
 * @property array attributes
 * @property string scenario
 * @package App\Services\Photos\Events
 */
class PhotoBeforeSave extends Event
{
    /**
     * PhotoBeforeSave constructor.
     *
     * @param Photo $photo
     * @param array $attributes
     * @param string $scenario
     */
    public function __construct(Photo &$photo, array &$attributes, string &$scenario)
    {
        $this->photo = &$photo;
        $this->attributes = &$attributes;
        $this->scenario = &$scenario;
    }
}
