<?php

namespace Lib\Rss\Contracts;

/**
 * Interface RssChannel.
 *
 * @package Lib\Rss\Contracts
 */
interface RssChannel
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
}
