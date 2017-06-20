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
     * Move object into the trash.
     *
     * @param string $filePath
     * @return void
     */
    public function move(string $filePath);

    /**
     * Restore object from the trash.
     *
     * @param string $filePath
     * @return void
     */
    public function restore(string $filePath);
}
