<?php

namespace App\Services\Photos\Exceptions;

use App\Services\Photos\Contracts\PhotoExceptionContract;
use Exception;

/**
 * Class PhotoException.
 * @package App\Services\Photos\Exceptions
 */
class PhotoException extends Exception implements PhotoExceptionContract
{

}
