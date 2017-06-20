<?php

namespace Core\FileSystem\Trash\Contracts;

/**
 * Interface Trash.
 *
 * @package Core\FileSystem\Trash\Contracts
 */
interface Trash
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
     * Restore object from the trash.
     *
     * @param string $objectPath
     * @return void
     */
    public function restore(string $objectPath);
}
