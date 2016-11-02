<?php

namespace App\Services\Photos\Exceptions;

use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;
use App\Services\Photos\Contracts\PhotoExceptionContract;

/**
 * Class PhotoValidationException.
 * @property Validator validator
 * @package App\Services\Photos\Exceptions
 */
class PhotoValidationException extends ValidationException implements PhotoExceptionContract
{

}
