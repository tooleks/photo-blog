<?php

namespace Lib\DataService\Exceptions;

use RuntimeException;
use Lib\DataService\Contracts\DataServiceException as DataServiceExceptionContract;

/**
 * Class DataServiceSavingException.
 *
 * @package Lib\DataService\Exceptions
 */
class DataServiceSavingException extends RuntimeException implements DataServiceExceptionContract
{

}
