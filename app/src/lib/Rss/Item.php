<?php

namespace Lib\Rss;

use Lib\Rss\Contracts\Enclosure;
use Lib\Rss\Contracts\Item as ItemContract;

/**
 * Class Item.
 *
 * @package Lib\Rss
 */
class Item implements ItemContract
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
     * @var string
     */
    protected $guid = '';

    /**
     * @var string
     */
    protected $pubDate = '';

    /**
     * @var Enclosure
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
    public function getGuid(): string
    {
        return $this->guid;
    }

    /**
     * @inheritdoc
     */
    public function setGuid(string $guid)
    {
        $this->guid = $guid;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getPubDate(): string
    {
        return $this->pubDate;
    }

    /**
     * @inheritdoc
     */
    public function setPubDate(string $pubDate)
    {
        $this->pubDate = $pubDate;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getEnclosure(): Enclosure
    {
        return $this->enclosure;
    }

    /**
     * @inheritdoc
     */
    public function setEnclosure(Enclosure $enclosure)
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
