<?php

namespace Core\Services\Contracts;

/**
 * Interface Revertable.
 *
 * @package Core\Services\Contracts
 */
interface Revertable
{
    /**
     * Revert the service action.
     *
     * @return mixed
     */
    public function revert();
}
