<?php

namespace Lib\SiteMap\Contracts;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Lib\SiteMap\Contracts\SiteMapItem as SiteMapItemContract;

/**
 * Interface SiteMapBuilder.
 *
 * @package Lib\SiteMap\Contracts
 */
interface SiteMapBuilder extends Arrayable
{
    /**
     * Add item to the site-map.
     *
     * @param SiteMapItem $item
     * @return $this
     */
    public function addItem(SiteMapItemContract $item);

    /**
     * Filter the site-map items.
     *
     * @param Closure $callback
     * @return void
     */
    public function filterItems(Closure $callback);

    /**
     * Get the site-map items.
     *
     * @return array
     */
    public function getItems() : array;

    /**
     * Erase the site-map items.
     *
     * @return void
     */
    public function eraseItems();
}
