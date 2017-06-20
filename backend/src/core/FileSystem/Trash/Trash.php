<?php

namespace Core\FileSystem\Trash;

use Core\FileSystem\Trash\Contracts\Trash as TrashContract;
use Illuminate\Contracts\Filesystem\Factory as Storage;
use RuntimeException;

/**
 * Class Trash.
 *
 * @package Core\FileSystem\Trash
 */
class Trash implements TrashContract
{
    /**
     * @var Storage
     */
    protected $storage;

    /**
     * @var string
     */
    protected $pathPrefix;

    /**
     * Trash constructor.
     *
     * @param Storage $storage
     * @param string $pathPrefix
     */
    public function __construct(Storage $storage, string $pathPrefix)
    {
        $this->storage = $storage;
        $this->pathPrefix = $pathPrefix;
    }

    /**
     * Get the object path in the trash.
     *
     * @param string|null $objectPath
     * @return string
     */
    protected function getObjectPath(string $objectPath = null): string
    {
        return $this->pathPrefix . ($objectPath ? '/' . $objectPath : '');
    }

    /**
     * @inheritdoc
     */
    public function move(string $filePath)
    {
        $this->storage->move($filePath, $this->getObjectPath($filePath));
    }

    /**
     * @inheritdoc
     */
    public function restore(string $filePath)
    {
        if (!$this->storage->has($this->getObjectPath($filePath))) {
            throw new RuntimeException(sprintf('The object "%s" not found in the trash.', $filePath));
        }

        $this->storage->move($this->getObjectPath($filePath), $filePath);
    }
}
