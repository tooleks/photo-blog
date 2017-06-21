<?php

namespace Core\Services\Photo;

use Core\Services\Photo\Contracts\FileSaverService as FileSaverServiceContract;
use Illuminate\Contracts\Filesystem\Factory as Storage;
use Illuminate\Http\UploadedFile;
use RuntimeException;

/**
 * Class FileSaverService.
 *
 * @property Storage storage
 * @property UploadedFile file
 * @property string path
 * @package Core\Services\Photo
 */
class FileSaverService implements FileSaverServiceContract
{
    /**
     * FileSaverService constructor.
     *
     * @param Storage $storage
     */
    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * Fetch parameters.
     *
     * @param array $parameters
     * @return void
     */
    protected function fetchParameters(array $parameters)
    {
        list($this->file, $this->path) = $parameters;
    }

    /**
     * @inheritdoc
     */
    public function run(...$parameters): string
    {
        $this->fetchParameters($parameters);

        $directoryPath = $this->path ?? config('main.storage.path.photos') . '/' . str_random(10);

        $filePath = $this->storage->put($directoryPath, $this->file);

        if ($filePath === false) {
            throw new RuntimeException(sprintf('File "%s" saving error.', $filePath));
        }

        return $filePath;
    }
}
