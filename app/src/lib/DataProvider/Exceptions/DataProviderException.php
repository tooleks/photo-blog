<?php

namespace Lib\DataProvider\Exceptions;

use Lib\DataProvider\Contracts\DataProviderException as DataProviderExceptionContract;
use RuntimeException;

/**
 * Class DataProviderException.
 *
 * @package Lib\DataProvider\Exceptions
 */
class DataProviderException extends RuntimeException implements DataProviderExceptionContract
{

}
