<?php

namespace Core\Managers\Trash;

use Core\Managers\Trash\Contracts\TrashManager as TrashManagerContract;
use Core\Managers\Trash\Exceptions\TrashManagerException;
use Illuminate\Contracts\Filesystem\Factory as Storage;
use Throwable;

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
        try {
            $this->storage->move($objectPath, $this->getObjectPath($objectPath));
        } catch (Throwable $e) {
            throw new TrashManagerException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @inheritdoc
     */
    public function moveIfExists(string $objectPath)
    {
        try {
            if ($this->storage->exists($objectPath)) {
                $this->storage->move($objectPath, $this->getObjectPath($objectPath));
            }
        } catch (Throwable $e) {
            throw new TrashManagerException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @inheritdoc
     */
    public function restore(string $objectPath)
    {
        try {
            $this->storage->move($this->getObjectPath($objectPath), $objectPath);
        } catch (Throwable $e) {
            throw new TrashManagerException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @inheritdoc
     */
    public function restoreIfExists(string $objectPath)
    {
        try {
            if ($this->storage->exists($this->getObjectPath($objectPath))) {
                $this->storage->move($this->getObjectPath($objectPath), $objectPath);
            }
        } catch (Throwable $e) {
            throw new TrashManagerException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @inheritdoc
     */
    public function clear(int $toTimestamp = null)
    {
        try {
            foreach ($this->storage->allDirectories($this->getObjectPath()) as $directory) {
                if (is_null($toTimestamp) || $toTimestamp > $this->storage->lastModified($directory)) {
                    $this->storage->deleteDirectory($directory);
                }
            }
            foreach ($this->storage->allFiles($this->getObjectPath()) as $file) {
                if (is_null($toTimestamp) || $toTimestamp > $this->storage->lastModified($file)) {
                    $this->storage->delete($file);
                }
            }
        } catch (Throwable $e) {
            throw new TrashManagerException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
