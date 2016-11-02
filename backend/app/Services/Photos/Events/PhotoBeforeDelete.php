<?php

namespace App\Services\Photos\Events;

use App\Events\Event;
use App\Models\Photo;

/**
 * Class PhotoBeforeDelete.
 * @property Photo photo
 * @package App\Services\Photos\Events
 */
class PhotoBeforeDelete extends Event
{
    /**
     * PhotoBeforeDelete constructor.
     *
     * @param Photo $photo
     */
    public function __construct(Photo &$photo)
    {
        $this->photo = &$photo;
    }
}
