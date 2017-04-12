<?php

namespace Lib\DataProvider\Exceptions;

use RuntimeException;
use Lib\DataProvider\Contracts\DataProviderException as DataProviderExceptionContract;

/**
 * Class DataProviderDeletingException.
 *
 * @package Lib\DataProvider\Exceptions
 */
class DataProviderDeletingException extends RuntimeException implements DataProviderExceptionContract
{

}
