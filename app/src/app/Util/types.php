<?php

namespace App\Util;

/**
 * @param $value
 * @return bool
 */
function to_bool($value): bool
{
    return (bool) $value;
}

/**
 * @param $value
 * @return null|int
 */
function to_int($value): ?int
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
function to_float($value): ?float
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
function to_string($value): ?string
{
    return $value ? (string) $value : null;
}

/**
 * @param $value
 * @return null|array
 */
function to_array($value): ?array
{
    return $value ? (array) $value : null;
}

/**
 * @param $value
 * @param string $className
 * @return null|object
 */
function to_object($value, string $className)
{
    return $value ? new $className($value) : null;
}
