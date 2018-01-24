<?php

namespace Lib\Rss\Contracts;

/**
 * Interface Item.
 *
 * @package Lib\Rss\Contracts
 */
interface Item
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
     * @return string
     */
    public function getGuid(): string;

    /**
     * @param string $guid
     * @return $this
     */
    public function setGuid(string $guid);

    /**
     * @return Enclosure
     */
    public function getEnclosure(): Enclosure;

    /**
     * @param Enclosure $enclosure
     * @return $this
     */
    public function setEnclosure(Enclosure $enclosure);

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
