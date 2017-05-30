<?php

namespace Lib\Rss;

use Lib\Rss\Contracts\RssItem as RssItemContract;
use Lib\Rss\Contracts\RssEnclosure;

/**
 * Class RssItem.
 *
 * @package Lib\Rss
 */
class RssItem implements RssItemContract
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
     * @var RssEnclosure
     */
    protected $enclosure;

    /**
     * @var array
     */
    protected $categories;

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

    /**
     * @inheritdoc
     */
    public function getEnclosure(): RssEnclosure
    {
        return $this->enclosure;
    }

    /**
     * @inheritdoc
     */
    public function setEnclosure(RssEnclosure $enclosure)
    {
        $this->enclosure = $enclosure;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * @inheritdoc
     */
    public function setCategories(array $categories)
    {
        $this->categories = $categories;

        return $this;
    }
}
