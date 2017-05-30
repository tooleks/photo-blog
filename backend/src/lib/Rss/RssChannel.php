<?php

namespace Lib\Rss;

use Lib\Rss\Contracts\RssChannel as RssChannelContract;

/**
 * Class RssChannel.
 *
 * @package Lib\Rss
 */
class RssChannel implements RssChannelContract
{
    /**
     * @var string
     */
    protected $title = '';

    /**
     * @var string
     */
    protected $link = '';

    /**
     * @var string
     */
    protected $description = '';

    /**
     * @inheritdoc
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @inheritdoc
     */
    public function setTitle(string $title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getLink(): string
    {
        return $this->link;
    }

    /**
     * @inheritdoc
     */
    public function setLink(string $link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @inheritdoc
     */
    public function setDescription(string $description)
    {
        $this->description = $description;

        return $this;
    }
}
