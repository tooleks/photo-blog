<?php

namespace Lib\ExifFetcher\Exception;

use Exception;
use Lib\ExifFetcher\Contracts\ExifFetcherException as ExifFetcherExceptionContract;

/**
 * Class ExifFetcherException.
 *
 * @package Lib\ExifFetcher\Exception
 */
class ExifFetcherException extends Exception implements ExifFetcherExceptionContract
{

}
