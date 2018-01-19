<?php

namespace Lib\DataProvider\Exceptions;

use Lib\DataProvider\Contracts\DataProviderException as DataProviderExceptionContract;
use RuntimeException;

/**
 * Class DataProviderSaveException.
 *
 * @package Lib\DataProvider\Exceptions
 */
class DataProviderSaveException extends RuntimeException implements DataProviderExceptionContract
{

}
