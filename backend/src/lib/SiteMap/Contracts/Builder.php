<?php

namespace Lib\SiteMap\Contracts;

/**
 * Interface Builder.
 *
 * @package Lib\SiteMap\Contracts
 */
interface Builder
{
    /**
     * @return array
     */
    public function getItems(): array;

    /**
     * @param array $items
     * @return $this
     */
    public function setItems(array $items);
}
