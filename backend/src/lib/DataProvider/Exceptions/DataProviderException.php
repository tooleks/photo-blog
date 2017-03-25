<?php

namespace Lib\DataProvider\Exceptions;

use Exception;
use Lib\DataProvider\Contracts\DataProviderException as DataProviderExceptionContract;

/**
 * Class DataProviderException.
 *
 * @package Lib\DataProvider\Exceptions
 */
class DataProviderException extends Exception implements DataProviderExceptionContract
{

}
