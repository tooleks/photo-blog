<?php

namespace App\Services\Thumbnails\Exceptions;

use App\Services\Thumbnails\Contracts\ThumbnailExceptionContract;
use Exception;

/**
 * Class ThumbnailException
 * @package App\Services\Thumbnails\Exceptions
 */
class ThumbnailException extends Exception implements ThumbnailExceptionContract
{

}
