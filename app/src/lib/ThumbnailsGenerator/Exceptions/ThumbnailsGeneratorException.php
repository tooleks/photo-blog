<?php

namespace Lib\ThumbnailsGenerator\Exceptions;

use Lib\ThumbnailsGenerator\Contracts\ThumbnailsGeneratorException as ThumbnailsGeneratorExceptionContract;
use Exception;

/**
 * Class ThumbnailsGeneratorException.
 *
 * @package Lib\ThumbnailsGenerator\Exceptions
 */
class ThumbnailsGeneratorException extends Exception implements ThumbnailsGeneratorExceptionContract
{

}
