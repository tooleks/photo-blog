<?php

namespace Core\Rss\Contracts;

use Lib\Rss\Contracts\Builder;

/**
 * Interface RssFeed.
 *
 * @package Core\Rss\Contracts
 */
interface RssFeed
{
    /**
     * Build the RSS feed.
     *
     * @return Builder
     */
    public function build(): Builder;
}
