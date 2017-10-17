<?php

namespace App\Util;

/**
 * Trait Normalizer.
 *
 * @package App\Util
 */
trait Normalizer
{
    /**
     * @param $value
     * @return null|bool
     */
    protected function normalizeBool($value): ?bool
    {
        return (bool) $value;
    }

    /**
     * @param $value
     * @return null|int
     */
    protected function normalizeInt($value): ?int
    {
        if (is_numeric($value) && $value == 0) {
            return (int) $value;
        }

        if (is_string($value) && $value == 0) {
            return (float) $value;
        }

        if ($value) {
            return (int) $value;
        }

        return null;
    }

    /**
     * @param $value
     * @return null|float
     */
    protected function normalizeFloat($value): ?float
    {
        if (is_numeric($value) && $value == 0) {
            return (float) $value;
        }

        if (is_string($value) && $value == 0) {
            return (float) $value;
        }

        if ($value) {
            return (float) $value;
        }

        return null;
    }

    /**
     * @param $value
     * @return null|string
     */
    protected function normalizeString($value): ?string
    {
        return $value ? (string) $value : null;
    }

    /**
     * @param $value
     * @return null|array
     */
    protected function normalizeArray($value): ?array
    {
        return $value ? (array) $value : null;
    }

    /**
     * @param $value
     * @param string $className
     * @return null|object
     */
    protected function normalizeClassObject($value, string $className)
    {
        return $value ? new $className($value) : null;
    }
}
