<?php

namespace App\Services\Photos\Events;

use App\Events\Event;
use App\Models\Photo;

/**
 * Class PhotoBeforeFind.
 * @property Photo photo
 * @property array parameters
 * @property string scenario
 * @package App\Services\Photos\Events
 */
class PhotoBeforeFind extends Event
{
    /**
     * PhotoBeforeFind constructor.
     *
     * @param Photo $photo
     * @param array $parameters
     * @param string $scenario
     */
    public function __construct(Photo &$photo, array &$parameters = [], &$scenario = null)
    {
        $this->photo = &$photo;
        $this->parameters = &$parameters;
        $this->scenario = &$scenario;
    }
}
