<?php

namespace App\Services\Photos\Events;

use App\Events\Event;
use App\Models\Photo;

/**
 * Class PhotoAfterDelete.
 * @property Photo $photo
 * @property int count
 * @package App\Services\Photos\Events
 */
class PhotoAfterDelete extends Event
{
    /**
     * PhotoAfterDelete constructor.
     *
     * @param Photo $photo
     * @param int $count
     */
    public function __construct(Photo &$photo, int &$count)
    {
        $this->photo = &$photo;
        $this->count = &$count;
    }
}
