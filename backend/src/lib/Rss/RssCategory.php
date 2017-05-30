<?php

namespace Lib\Rss;

use Lib\Rss\Contracts\RssCategory as RssCategoryContract;

/**
 * Class RssCategory.
 *
 * @package Lib\Rss
 */
class RssCategory implements RssCategoryContract
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
