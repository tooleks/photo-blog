<?php

namespace App\Services\Manifest\Contracts;

/**
 * Interface ManifestService.
 *
 * @package App\Services\SiteMap\Contracts
 */
interface ManifestService
{
    /**
     * Get manifest content.
     *
     * @return array
     */
    public function get(): array;
}
