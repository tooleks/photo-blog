<?php

namespace Lib\SiteMap\Contracts;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Lib\SiteMap\Contracts\Item as ItemContract;

/**
 * Interface Builder.
 *
 * @package Lib\SiteMap\Contracts
 */
interface Builder extends Arrayable
{
    /**
     * Add item to the site-map.
     *
     * @param Item $item
     * @return $this
     */
    public function addItem(ItemContract $item);

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
    public function getItems(): array;

    /**
     * Erase the site-map items.
     *
     * @return void
     */
    public function eraseItems();
}
