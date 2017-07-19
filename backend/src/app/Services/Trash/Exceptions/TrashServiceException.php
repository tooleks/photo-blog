<?php

namespace App\Services\Trash\Exceptions;

use App\Services\Trash\Contracts\TrashServiceException as TrashServiceExceptionContract;
use Exception;

/**
 * Class TrashServiceException.
 *
 * @package App\Services\Trash\Exceptions
 */
class TrashServiceException extends Exception implements TrashServiceExceptionContract
{

}
