<?php

namespace Core\Services\Photo;

use Core\Services\Photo\Contracts\ExifFetcherService as ExifFetcherServiceContract;
use Lib\ExifFetcher\Contracts\ExifFetcher;

/**
 * Class ExifFetcherService.
 *
 * @package Core\Services\Photo
 */
class ExifFetcherService implements ExifFetcherServiceContract
{
    /**
     * @var ExifFetcher
     */
    private $exifFetcher;

    /**
     * @var mixed
     */
    private $file;

    /**
     * ExifFetcherService constructor.
     *
     * @param ExifFetcher $exifFetcher
     */
    public function __construct(ExifFetcher $exifFetcher)
    {
        $this->exifFetcher = $exifFetcher;
    }

    /**
     * Fetch parameters.
     *
     * @param array $parameters
     */
    protected function fetchParameters(array $parameters)
    {
        list($this->file) = $parameters;
    }

    /**
     * @inheritdoc
     */
    public function run(...$parameters): array
    {
        $this->fetchParameters($parameters);

        $data = $this->exifFetcher->fetch($this->file->getPathname());

        // Replace the temporary file name with the original one.
        $data['FileName'] = $this->file->getClientOriginalName();

        return ['data' => $data];
    }
}
