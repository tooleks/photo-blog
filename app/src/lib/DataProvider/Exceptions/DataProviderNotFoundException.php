<?php

namespace Lib\DataProvider\Exceptions;

use Lib\DataProvider\Contracts\DataProviderException as DataProviderExceptionContract;
use RuntimeException;

/**
 * Class DataProviderNotFoundException.
 *
 * @package Lib\DataProvider\Exceptions
 */
class DataProviderNotFoundException extends RuntimeException implements DataProviderExceptionContract
{

}
