<?php

namespace Core\Services\Photo;

use Core\Services\Contracts\Service;
use Illuminate\Contracts\Filesystem\Factory as Storage;
use Illuminate\Validation\ValidationException;
use RuntimeException;

/**
 * Class FileSaverService.
 *
 * @property Storage storage
 * @package Core\Services\Photo
 */
class FileSaverService implements Service
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
     * @inheritdoc
     */
    public function run(...$parameters)
    {
        list($photo, $file) = $parameters;

        $fileDirectoryRelPath = $photo->directory_path ?? config('main.storage.photos') . '/' . str_random(10);

        if (($fileRelPath = $this->storage->put($fileDirectoryRelPath, $file)) === false) {
            throw new RuntimeException(sprintf('File "%s" saving error.', $fileRelPath));
        }

        $photo->path = $fileRelPath;
        $photo->relative_url = $this->storage->url($fileRelPath);
    }
}
