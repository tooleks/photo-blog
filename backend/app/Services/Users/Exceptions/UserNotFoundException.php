<?php

namespace App\Services\Users\Exceptions;

use App\Services\Users\Contracts\UserExceptionContract;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class UserNotFoundException.
 * @package App\Services\Users\Exceptions
 */
class UserNotFoundException extends ModelNotFoundException implements UserExceptionContract
{

}
