<?php

namespace App\ValueObjects;

use InvalidArgumentException;

/**
 * Class Latitude.
 *
 * @package App\ValueObjects
 */
final class Latitude
{
    private $value;

    /**
     * Latitude constructor.
     *
     * @param float $value
     */
    public function __construct(float $value)
    {
        $this->assertValue($value);

        $this->value = $value;
    }

    /**
     * @param $value
     * @return void
     * @throws InvalidArgumentException
     */
    private function assertValue(float $value): void
    {
        if ($value < -90 && $value > 90) {
            throw new InvalidArgumentException('Invalid latitude value.');
        }
    }

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->getValue();
    }
}
