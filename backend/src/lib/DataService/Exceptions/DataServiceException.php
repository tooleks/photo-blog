<?php

namespace Lib\DataService\Exceptions;

use Exception;
use Lib\DataService\Contracts\DataServiceException as DataServiceExceptionContract;

/**
 * Class DataServiceException.
 *
 * @package Lib\DataService\Exceptions
 */
class DataServiceException extends Exception implements DataServiceExceptionContract
{

}
