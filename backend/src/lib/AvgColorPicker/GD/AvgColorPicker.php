<?php

namespace Lib\AvgColorPicker\GD;

use Closure;
use Lib\AvgColorPicker\ColorConverter;
use Lib\AvgColorPicker\Contracts\AvgColorPicker as AvgColorPickerContract;
use RuntimeException;

/**
 * Class AvgColorPicker.
 *
 * @package Lib\AvgColorPicker\GD
 */
class AvgColorPicker implements AvgColorPickerContract
{
    /**
     * @inheritdoc
     */
    public function getImageAvgHexColorByPath(string $imagePath) : string
    {
        $avgRgbColor = [];

        $this->eachImagePixel($this->createImageResource($imagePath), function ($imageResource, $xCoordinate, $yCoordinate) use (&$avgRgbColor) {
            $pixelRgbColor = $this->getImagePixelRgbColor($imageResource, $xCoordinate, $yCoordinate);
            $avgRgbColor = $avgRgbColor ? $this->calculateAvgRgbColor($avgRgbColor, $pixelRgbColor) : $pixelRgbColor;
        });

        return (new ColorConverter)->rgb2hex($avgRgbColor);
    }

    /**
     * Create image resource.
     *
     * @param string $imagePath
     * @return resource
     */
    private function createImageResource(string $imagePath)
    {
        if (!file_exists($imagePath)) {
            throw new RuntimeException(sprintf('The "%s" file not exist.', $imagePath));
        }

        $imageCreateFunctions = [
            'image/png' => 'imagecreatefrompng',
            'image/jpeg' => 'imagecreatefromjpeg',
            'image/gif' => 'imagecreatefromgif',
        ];

        $imageMimeType = mime_content_type($imagePath);

        if (!array_key_exists($imageMimeType, $imageCreateFunctions)) {
            throw new RuntimeException(sprintf('The "%s" mime type not supported.', $imageMimeType));
        }

        return call_user_func($imageCreateFunctions[$imageMimeType], $imagePath);
    }

    /**
     * Apply callback function on each pixel in the image.
     *
     * @param $imageResource
     * @param Closure $callback
     * @return void
     */
    private function eachImagePixel($imageResource, Closure $callback)
    {
        $imageWidth = imagesx($imageResource);
        $imageHeight = imagesy($imageResource);

        for ($xCoordinate = 0; $xCoordinate < $imageWidth; $xCoordinate++) {
            for ($yCoordinate = 0; $yCoordinate < $imageHeight; $yCoordinate++) {
                $callback($imageResource, $xCoordinate, $yCoordinate);
            }
        }
    }

    /**
     * Get image pixel color in RGB format.
     *
     * @param resource $imageResource
     * @param int $xCoordinate
     * @param int $yCoordinate
     * @return array
     */
    private function getImagePixelRgbColor($imageResource, int $xCoordinate, int $yCoordinate) : array
    {
        $rgb = imagecolorsforindex($imageResource, imagecolorat($imageResource, $xCoordinate, $yCoordinate));

        return array_values($rgb);
    }

    /**
     * Calculate average color in RGB format.
     *
     * @param array $firstRgb
     * @param array $secondRgb
     * @return array
     */
    private function calculateAvgRgbColor(array $firstRgb, array $secondRgb) : array
    {
        $avgRgb = [];

        $avgRgb[0] = (int)(($firstRgb[0] + $secondRgb[0]) / 2); // RED
        $avgRgb[1] = (int)(($firstRgb[1] + $secondRgb[1]) / 2); // GREEN
        $avgRgb[2] = (int)(($firstRgb[2] + $secondRgb[2]) / 2); // BLUE

        return $avgRgb;
    }
}
