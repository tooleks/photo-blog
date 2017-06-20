<?php

namespace Core\Services\Photo;

use Core\Services\Photo\Contracts\FileSaverService as FileSaverServiceContract;
use Illuminate\Contracts\Filesystem\Factory as Storage;
use RuntimeException;

/**
 * Class FileSaverService.
 *
 * @property Storage storage
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
     * @inheritdoc
     */
    public function run(...$parameters)
    {
        list($photo, $file) = $parameters;

        $directoryRelPath = $photo->directory_path ?? config('main.storage.path.photos') . '/' . str_random(10);

        if (($fileRelPath = $this->storage->put($directoryRelPath, $file)) === false) {
            throw new RuntimeException(sprintf('File "%s" saving error.', $fileRelPath));
        }

        $photo->path = $fileRelPath;
        $photo->relative_url = $this->storage->url($fileRelPath);
    }
}
