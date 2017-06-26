<?php

namespace Core\Services\Trash\Contracts;

/**
 * Interface TrashService.
 *
 * @package Core\Services\Trash\Contracts
 */
interface TrashService
{
    /**
     * Determine the trash has object.
     *
     * @param string $objectPath
     * @return bool
     */
    public function has(string $objectPath): bool;

    /**
     * Move object into the trash.
     *
     * @param string $objectPath
     * @return void
     */
    public function move(string $objectPath);

    /**
     * Move object into the trash if it exists.
     *
     * @param string $objectPath
     * @return void
     */
    public function moveIfExists(string $objectPath);

    /**
     * Restore object from the trash.
     *
     * @param string $objectPath
     * @return void
     */
    public function restore(string $objectPath);

    /**
     * Restore object from the trash if it exists.
     *
     * @param string $objectPath
     * @return void
     */
    public function restoreIfExists(string $objectPath);

    /**
     * Clear the trash objects.
     *
     * @param int|null $toTimestamp
     * @return void
     */
    public function clear(int $toTimestamp = null);
}
