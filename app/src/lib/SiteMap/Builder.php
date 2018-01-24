<?php

namespace Lib\SiteMap;

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
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @inheritdoc
     */
    public function setItems(array $items)
    {
        $this->items = $items;

        return $this;
    }
}
