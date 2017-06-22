<?php

namespace Core\Managers\Trash;

use Core\Managers\Trash\Contracts\TrashManager as TrashManagerContract;
use Illuminate\Contracts\Filesystem\Factory as Storage;
use Carbon\Carbon;

/**
 * Class TrashManager.
 *
 * @package Core\Managers\Trash
 */
class TrashManager implements TrashManagerContract
{
    /**
     * @var Storage
     */
    private $storage;

    /**
     * @var string
     */
    private $pathPrefix;

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
    private function getObjectPath(string $objectPath = null): string
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
    public function moveIfExists(string $objectPath)
    {
        if ($this->storage->exists($objectPath)) {
            $this->storage->move($objectPath, $this->getObjectPath($objectPath));
        }
    }

    /**
     * @inheritdoc
     */
    public function restore(string $objectPath)
    {
        $this->storage->move($this->getObjectPath($objectPath), $objectPath);
    }

    /**
     * @inheritdoc
     */
    public function restoreIfExists(string $objectPath)
    {
        if ($this->storage->exists($this->getObjectPath($objectPath))) {
            $this->storage->move($this->getObjectPath($objectPath), $objectPath);
        }
    }

    /**
     * @inheritdoc
     */
    public function clear()
    {
        foreach ($this->storage->directories($this->getObjectPath()) as $directory) {
            $this->storage->deleteDirectory($directory);
        }

        foreach ($this->storage->files($this->getObjectPath()) as $file) {
            $this->storage->delete($file);
        }
    }

    /**
     * @inheritdoc
     */
    public function clearOlderThanDay()
    {
        $timestamp = Carbon::now()->addDay(-1)->timestamp;

        foreach ($this->storage->allDirectories($this->getObjectPath()) as $directory) {
            if ($this->storage->lastModified($directory) < $timestamp) {
                $this->storage->deleteDirectory($directory);
            }
        }

        foreach ($this->storage->allFiles($this->getObjectPath()) as $file) {
            if ($this->storage->lastModified($file) < $timestamp) {
                $this->storage->file($file);
            }
        }
    }
}
