<?php

namespace Lib\Rss;

use Lib\Rss\Contracts\RssBuilder as RssBuilderContract;
use Lib\Rss\Contracts\RssChannel;

/**
 * Class RssBuilder.
 *
 * @package Lib\Rss
 */
class RssBuilder implements RssBuilderContract
{
    /**
     * @var RssChannel
     */
    protected $channel;

    /**
     * @var array
     */
    protected $items;

    /**
     * @inheritdoc
     */
    public function getChannel(): RssChannel
    {
        return $this->channel;
    }

    /**
     * @inheritdoc
     */
    public function setChannel(RssChannel $channel)
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @inheritdoc
     */
    public function setItems(array $items)
    {
        $this->items = $items;

        return $this;
    }
}
