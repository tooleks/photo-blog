<?php

namespace App\Services\Photos\Events;

use App\Events\Event;

/**
 * Class PhotoAfterFind.
 * @property mixed data
 * @package App\Services\Photos\Events
 */
class PhotoAfterFind extends Event
{
    /**
     * PhotoAfterFind constructor.
     * 
     * @param mixed $data
     */
    public function __construct(&$data)
    {
        $this->data = &$data;
    }
}
