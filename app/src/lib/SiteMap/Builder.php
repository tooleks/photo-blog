<?php

namespace Lib\SiteMap;

use Lib\SiteMap\Contracts\Builder as BuilderContract;
use Lib\SiteMap\Contracts\Item as ItemContract;

/**
 * Class SiteMapBuilder.
 *
 * @package Lib\SiteMap
 */
class Builder implements BuilderContract
{
    /**
     * @var array
     */
    protected $items = [];

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
    public function setItems(array $items)
    {
        $this->items = array_map(function (ItemContract $item) {
            return $item;
        }, $items);

        return $this;
    }
}
