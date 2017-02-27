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
     * Get image average color in HEX format of by its path.
     *
     * @param string $imagePath
     * @return string
     */
    public function getImageAvgColorByPath(string $imagePath) : string;
}
