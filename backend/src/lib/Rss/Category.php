<?php

namespace Lib\Rss;

use Lib\Rss\Contracts\Category as CategoryContract;

/**
 * Class Category.
 *
 * @package Lib\Rss
 */
class Category implements CategoryContract
{
    /**
     * @var string
     */
    protected $value = '';

    /**
     * @inheritdoc
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @inheritdoc
     */
    public function setValue(string $value)
    {
        $this->value = $value;

        return $this;
    }
}
