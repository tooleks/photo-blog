<?php

namespace Core\Managers\Trash\Exceptions;

use Core\Managers\Trash\Contracts\TrashManagerException as TrashManagerExceptionContract;
use Exception;

/**
 * Class TrashManagerException.
 *
 * @package Core\Managers\Trash\Exceptions
 */
class TrashManagerException extends Exception implements TrashManagerExceptionContract
{

}
