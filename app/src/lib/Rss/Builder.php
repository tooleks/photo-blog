<?php

namespace Lib\Rss;

use Lib\Rss\Contracts\Builder as BuilderContract;
use Lib\Rss\Contracts\Channel;

/**
 * Class Builder.
 *
 * @package Lib\Rss
 */
class Builder implements BuilderContract
{
    /**
     * @var Channel
     */
    protected $channel;

    /**
     * @var array
     */
    protected $items;

    /**
     * @inheritdoc
     */
    public function getChannel(): Channel
    {
        return $this->channel;
    }

    /**
     * @inheritdoc
     */
    public function setChannel(Channel $channel)
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
