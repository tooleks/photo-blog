<?php

namespace Core\FileSystem\Trash;

use Core\FileSystem\Trash\Contracts\Trash as TrashContract;
use Illuminate\Contracts\Filesystem\Factory as Storage;

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
    public function has(string $objectPath): bool
    {
        return $this->storage->has($this->getObjectPath($objectPath));
    }

    /**
     * @inheritdoc
     */
    public function move(string $objectPath)
    {
        $this->storage->move($objectPath, $this->getObjectPath($objectPath));
    }

    /**
     * @inheritdoc
     */
    public function restore(string $objectPath)
    {
        $this->storage->move($this->getObjectPath($objectPath), $objectPath);
    }
}
