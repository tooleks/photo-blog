<?php

namespace Lib\DataProvider\Exceptions;

use RuntimeException;
use Lib\DataProvider\Contracts\DataProviderException as DataProviderExceptionContract;

/**
 * Class DataProviderSavingException.
 *
 * @package Lib\DataProvider\Exceptions
 */
class DataProviderSavingException extends RuntimeException implements DataProviderExceptionContract
{

}
