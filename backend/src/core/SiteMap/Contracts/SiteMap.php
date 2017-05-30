<?php

namespace Core\SiteMap\Contracts;

use Lib\SiteMap\Contracts\SiteMapBuilder;

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
     * @return SiteMapBuilder
     */
    public function build(): SiteMapBuilder;
}
