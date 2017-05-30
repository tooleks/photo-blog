<?php

namespace Lib\Rss\Contracts;

/**
 * Interface RssCategory.
 *
 * @package Lib\Rss\Contracts
 */
interface RssCategory
{
    /**
     * @return string
     */
    public function getValue(): string;

    /**
     * @param string $value
     * @return $this
     */
    public function setValue(string $value);
}
