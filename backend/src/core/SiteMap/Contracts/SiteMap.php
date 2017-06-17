<?php

namespace Core\SiteMap\Contracts;

use Lib\SiteMap\Contracts\Builder;

/**
 * Interface SiteMap.
 *
 * @package Core\SiteMap\Contracts
 */
interface SiteMap
{
    /**
     * Build the site map.
     *
     * @return Builder
     */
    public function build(): Builder;
}
