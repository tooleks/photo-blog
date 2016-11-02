<?php

namespace App\Services\Users\Exceptions;

use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use App\Services\Users\Contracts\UserExceptionContract;

/**
 * Class UserValidationException.
 * @property Validator validator
 * @package App\Services\Users\Exceptions
 */
class UserValidationException extends ValidationException implements UserExceptionContract
{

}
