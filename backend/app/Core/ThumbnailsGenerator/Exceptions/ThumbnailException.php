<?php

namespace App\Core\ThumbnailsGenerator\Exceptions;

use App\Core\ThumbnailsGenerator\Contracts\ThumbnailExceptionContract;
use Exception;

/**
 * Class ThumbnailException
 * @package App\Core\ThumbnailsGenerator\Exceptions
 */
class ThumbnailException extends Exception implements ThumbnailExceptionContract
{

}
