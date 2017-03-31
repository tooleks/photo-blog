<?php

namespace Lib\AvgColorPicker;

use RuntimeException;

/**
 * Class ColorConverter.
 *
 * @package Lib\AvgColorPicker
 */
class ColorConverter
{
    /**
     * Convert color value in HEX to RGB format.
     *
     * Example: (string) HEX #000000 -> (array) RGB [0, 0, 0]
     *
     * @param string $hex
     * @return array
     */
    public function hex2rgb(string $hex) : array
    {
        if (count($hex) !== 7) {
            throw new RuntimeException(sprintf('Invalid HEX value %s.', $hex));
        }

        return sscanf($hex, "#%02x%02x%02x");
    }

    /**
     * Convert color value in RGB to HEX format.
     *
     * Example: (array) RGB [0, 0, 0] -> (string) HEX #000000
     *
     * @param array $rgb
     * @return string
     */
    public function rgb2hex(array $rgb) : string
    {
        if (count($rgb) !== 3) {
            throw new RuntimeException(sprintf('Invalid RGB value [%s].', implode(', ', $rgb)));
        }

        return '#' . sprintf('%02x%02x%02x', (int)$rgb[0], (int)$rgb[1], (int)$rgb[2]);
    }
}
