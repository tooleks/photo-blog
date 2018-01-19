<?php

namespace App\Util;

/**
 * Class CastValue.
 *
 * @package App\Util
 */
class CastValue
{
    /**
     * @param $value
     * @return null|bool
     */
    public static function toBool($value): bool
    {
        return (bool) $value;
    }

    /**
     * @param $value
     * @return null|int
     */
    public static function toIntOrNull($value): ?int
    {
        if (is_numeric($value) && $value == 0) {
            return (int) $value;
        }

        if (is_string($value) && $value == 0) {
            return (int) $value;
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
    public static function toFloatOrNull($value): ?float
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
    public static function toStringOrNull($value): ?string
    {
        return $value ? (string) $value : null;
    }

    /**
     * @param $value
     * @return null|array
     */
    public static function toArrayOrNull($value): ?array
    {
        return $value ? (array) $value : null;
    }

    /**
     * @param $value
     * @param string $className
     * @return null|object
     */
    public static function toClassObjectOrNull($value, string $className)
    {
        return $value ? new $className($value) : null;
    }
}
