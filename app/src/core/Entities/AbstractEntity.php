<?php

namespace Core\Entities;

use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

/**
 * Class AbstractEntity.
 *
 * @package Core\Entities
 */
abstract class AbstractEntity implements Arrayable, JsonSerializable
{
    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
