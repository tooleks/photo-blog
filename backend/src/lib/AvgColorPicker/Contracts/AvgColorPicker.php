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
     * Get average hex color code of the image by its path.
     *
     * @param string $imagePath
     * @return string
     */
    public function getImageAvgHexColorByPath(string $imagePath) : string;
}
