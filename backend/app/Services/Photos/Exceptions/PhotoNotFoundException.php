<?php

namespace App\Services\Photos\Exceptions;

use App\Services\Photos\Contracts\PhotoExceptionContract;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class PhotoNotFoundException.
 * @package App\Services\Photos\Exceptions
 */
class PhotoNotFoundException extends ModelNotFoundException implements PhotoExceptionContract
{

}
