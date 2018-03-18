<?php

namespace App\ValueObjects;

use InvalidArgumentException;

/**
 * Class Longitude.
 *
 * @package App\ValueObjects
 */
final class Longitude
{
    private $value;

    /**
     * Longitude constructor.
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
        if ($value < -180 && $value > 180) {
            throw new InvalidArgumentException('Invalid longitude value.');
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
