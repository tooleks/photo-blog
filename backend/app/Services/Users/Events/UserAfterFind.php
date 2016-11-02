<?php

namespace App\Services\Users\Events;

use App\Events\Event;

/**
 * Class UserAfterFind.
 * @property mixed data
 * @package App\Services\Users\Events
 */
class UserAfterFind extends Event
{
    /**
     * UserAfterFind constructor.
     * 
     * @param mixed $data
     */
    public function __construct(&$data)
    {
        $this->data = &$data;
    }
}
