<?php

namespace Lib\Rss\Contracts;

/**
 * Interface Enclosure.
 *
 * @package Lib\Rss\Contracts
 */
interface Enclosure
{
    /**
     * @return string
     */
    public function getUrl(): string;

    /**
     * @param string $url
     * @return $this;
     */
    public function setUrl(string $url);

    /**
     * @return string
     */
    public function getLength(): string;

    /**
     * @param string $length
     * @return $this;
     */
    public function setLength(string $length);

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @param string $type
     * @return $this;
     */
    public function setType(string $type);
}
