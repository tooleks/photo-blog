<?php

namespace Lib\AvgColorPicker\Contracts;

/**
 * Interface AvgColorPicker.
 *
 * @package Lib\AvgColorPicker\Contracts
 */
interface AvgColorPicker
{
    /**
     * Get average color in HEX format of the image by its path..
     *
     * @param string $imagePath
     * @return string
     */
    public function getAvgColorByImagePath(string $imagePath) : string;
}
