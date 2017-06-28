<?php

namespace Core\Services\Trash;

use Core\Services\Trash\Contracts\TrashService as TrashServiceContract;
use Core\Services\Trash\Exceptions\TrashServiceException;
use Illuminate\Contracts\Filesystem\Factory as Storage;
use Throwable;

/**
 * Class TrashService.
 *
 * @package Core\Services\Trash
 */
class TrashService implements TrashServiceContract
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
        try {
            return $this->storage->has($this->getObjectPath($objectPath));
        } catch (Throwable $e) {
            throw new TrashServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @inheritdoc
     */
    public function move(string $objectPath): void
    {
        try {
            $this->storage->move($objectPath, $this->getObjectPath($objectPath));
        } catch (Throwable $e) {
            throw new TrashServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @inheritdoc
     */
    public function moveIfExists(string $objectPath): void
    {
        try {
            if ($this->storage->exists($objectPath)) {
                $this->storage->move($objectPath, $this->getObjectPath($objectPath));
            }
        } catch (Throwable $e) {
            throw new TrashServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @inheritdoc
     */
    public function restore(string $objectPath): void
    {
        try {
            $this->storage->move($this->getObjectPath($objectPath), $objectPath);
        } catch (Throwable $e) {
            throw new TrashServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @inheritdoc
     */
    public function restoreIfExists(string $objectPath): void
    {
        try {
            if ($this->storage->exists($this->getObjectPath($objectPath))) {
                $this->storage->move($this->getObjectPath($objectPath), $objectPath);
            }
        } catch (Throwable $e) {
            throw new TrashServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @inheritdoc
     */
    public function clear(int $toTimestamp = null): void
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
            throw new TrashServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
