<?php

namespace App\Services\Manifest\Contracts;

/**
 * Interface Manifest.
 *
 * @package App\Services\SiteMap\Contracts
 */
interface Manifest
{
    /**
     * Get manifest content.
     *
     * @return array
     */
    public function get(): array;
}
