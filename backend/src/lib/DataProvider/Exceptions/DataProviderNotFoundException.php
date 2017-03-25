<?php

namespace Lib\DataProvider\Exceptions;

use RuntimeException;
use Lib\DataProvider\Contracts\DataProviderException as DataProviderExceptionContract;

/**
 * Class DataProviderNotFoundException.
 *
 * @package Lib\DataProvider\Exceptions
 */
class DataProviderNotFoundException extends RuntimeException implements DataProviderExceptionContract
{

}
