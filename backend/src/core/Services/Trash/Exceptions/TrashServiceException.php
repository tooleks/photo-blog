<?php

namespace Core\Services\Trash\Exceptions;

use Core\Services\Trash\Contracts\TrashServiceException as TrashServiceExceptionContract;
use Exception;

/**
 * Class TrashServiceException.
 *
 * @package Core\Services\Trash\Exceptions
 */
class TrashServiceException extends Exception implements TrashServiceExceptionContract
{

}
