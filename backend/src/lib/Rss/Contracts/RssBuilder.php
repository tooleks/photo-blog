<?php

namespace Lib\Rss\Contracts;

/**
 * Interface RssBuilder.
 *
 * @package Lib\Rss\Contracts
 */
interface RssBuilder
{
    /**
     * @return RssChannel
     */
    public function getChannel(): RssChannel;

    /**
     * @param RssChannel $channel
     * @return $this
     */
    public function setChannel(RssChannel $channel);

    /**
     * @return array
     */
    public function getItems(): array;

    /**
     * @param array $items
     * @return $this
     */
    public function setItems(array $items);
}
