<?php

namespace Lib\DataProvider\Exceptions;

use Lib\DataProvider\Contracts\DataProviderException as DataProviderExceptionContract;
use RuntimeException;

/**
 * Class DataProviderDeleteException.
 *
 * @package Lib\DataProvider\Exceptions
 */
class DataProviderDeleteException extends RuntimeException implements DataProviderExceptionContract
{

}
