<?php

namespace Lib\DataService\Exceptions;

use RuntimeException;
use Lib\DataService\Contracts\DataServiceException as DataServiceExceptionContract;

/**
 * Class DataServiceNotFoundException.
 *
 * @package Lib\DataService\Exceptions
 */
class DataServiceNotFoundException extends RuntimeException implements DataServiceExceptionContract
{

}
