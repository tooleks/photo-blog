<?php

namespace Lib\SiteMap;

use Closure;
use Lib\SiteMap\Contracts\SiteMapItem as SiteMapItemContract;
use Lib\SiteMap\Contracts\SiteMapBuilder as SiteMapBuilderContract;

/**
 * Class SiteMapBuilder.
 *
 * @package Lib\SiteMap
 */
class SiteMapBuilder implements SiteMapBuilderContract
{
    protected $items = [];

    /**
     * @inheritdoc
     */
    public function addItem(SiteMapItemContract $item)
    {
        array_push($this->items, $item);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function filterItems(Closure $callback)
    {
        $this->items = array_filter($this->items, $callback);
    }

    /**
     * @inheritdoc
     */
    public function getItems() : array
    {
        return $this->items;
    }

    /**
     * @inheritdoc
     */
    public function eraseItems()
    {
        $this->items = [];
    }

    /**
     * @inheritdoc
     */
    public function toArray()
    {
        return $this->getItems();
    }
}
