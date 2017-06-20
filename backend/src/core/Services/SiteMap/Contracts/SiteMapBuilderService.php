<?php

namespace Core\Services\SiteMap\Contracts;

use Core\Services\Contracts\Runnable;
use Lib\SiteMap\Contracts\Builder;

/**
 * Interface SiteMapBuilderService.
 *
 * @package Core\Services\SiteMap\Contracts
 */
interface SiteMapBuilderService extends Runnable
{
    /**
     * Build the sitemap.
     *
     * @param array ...$parameters
     * @return Builder
     */
    public function run(...$parameters): Builder;
}
