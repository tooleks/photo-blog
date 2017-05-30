<?php

namespace Core\Rss\Contracts;

use Lib\Rss\Contracts\RssBuilder;

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
     * @return RssBuilder
     */
    public function build(): RssBuilder;
}
