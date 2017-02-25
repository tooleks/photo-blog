<?php

namespace Lib\DataService\Exceptions;

use RuntimeException;
use Lib\DataService\Contracts\DataServiceException as DataServiceExceptionContract;

/**
 * Class DataServiceDeletingException.
 *
 * @package Lib\DataService\Exceptions
 */
class DataServiceDeletingException extends RuntimeException implements DataServiceExceptionContract
{

}
