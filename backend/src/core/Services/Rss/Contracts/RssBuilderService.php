<?php

namespace Core\Services\Rss\Contracts;

use Lib\Rss\Contracts\Builder;

/**
 * Interface RssBuilderService.
 *
 * @package Core\Services\Rss\Contracts
 */
interface RssBuilderService
{
    /**
     * Build the RSS feed.
     *
     * @return Builder
     */
    public function build(): Builder;
}
