<?php

namespace Lib\AvgColorPicker;

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
     * Example: HEX #000000 -> RGB [0, 0, 0]
     *
     * @param string $hex
     * @return array
     */
    public function hex2rgb(string $hex) : array
    {
        return sscanf($hex, "#%02x%02x%02x");
    }

    /**
     * Convert color value in RGB to HEX format.
     *
     * Example: RGB [0, 0, 0] -> HEX #000000
     *
     * @param array $rgb
     * @return string
     */
    public function rgb2hex(array $rgb) : string
    {
        return '#' . sprintf('%02x', $rgb[0]) . sprintf('%02x', $rgb[1]) . sprintf('%02x', $rgb[2]);
    }
}
