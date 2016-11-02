<?php

namespace App\Services\Users\Exceptions;

use App\Services\Users\Contracts\UserExceptionContract;
use Exception;

/**
 * Class UserException.
 * @package App\Services\Users\Exceptions
 */
class UserException extends Exception implements UserExceptionContract
{

}
