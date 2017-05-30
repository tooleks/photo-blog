<?php

namespace Lib\Rss\Contracts;

/**
 * Interface RssItem.
 *
 * @package Lib\Rss\Contracts
 */
interface RssItem
{
    /**
     * @return string
     */
    public function getTitle(): string;

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title);

    /**
     * @return string
     */
    public function getLink(): string;

    /**
     * @param string $link
     * @return $this
     */
    public function setLink(string $link);

    /**
     * @return string
     */
    public function getDescription(): string;

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description);

    /**
     * @return RssEnclosure
     */
    public function getEnclosure(): RssEnclosure;

    /**
     * @param RssEnclosure $enclosure
     * @return $this
     */
    public function setEnclosure(RssEnclosure $enclosure);

    /**
     * @return array
     */
    public function getCategories(): array;

    /**
     * @param array $categories
     * @return $this
     */
    public function setCategories(array $categories);
}
