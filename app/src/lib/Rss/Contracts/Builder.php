<?php

namespace Lib\Rss\Contracts;

/**
 * Interface Builder.
 *
 * @package Lib\Rss\Contracts
 */
interface Builder
{
    /**
     * @return Channel
     */
    public function getChannel(): Channel;

    /**
     * @param Channel $channel
     * @return $this
     */
    public function setChannel(Channel $channel);

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
