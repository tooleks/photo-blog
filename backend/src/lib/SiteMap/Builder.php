<?php

namespace Lib\SiteMap;

use Closure;
use Lib\SiteMap\Contracts\Item as ItemContract;
use Lib\SiteMap\Contracts\Builder as BuilderContract;

/**
 * Class SiteMapBuilder.
 *
 * @package Lib\SiteMap
 */
class Builder implements BuilderContract
{
    protected $items = [];

    /**
     * @inheritdoc
     */
    public function addItem(ItemContract $item)
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
    public function getItems(): array
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
