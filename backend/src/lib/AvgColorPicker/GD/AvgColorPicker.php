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
     * MIME types map to image resource creation functions.
     *
     * @var array
     */
    private $mimeTypesMap = [
        'image/png' => 'imagecreatefrompng',
        'image/jpeg' => 'imagecreatefromjpeg',
        'image/gif' => 'imagecreatefromgif',
    ];

    /**
     * @inheritdoc
     */
    public function getAvgColorByImagePath(string $imagePath) : string
    {
        $avgRgb = [];

        $this->eachImagePixel($this->createImageResource($imagePath), function ($imageResource, $x, $y) use (&$avgRgb) {
            $rgb = $this->getImageRbgForIndex($imageResource, $x, $y);
            $avgRgb = $avgRgb ? $this->calculateAvgRgb($avgRgb, $rgb) : $rgb;
        });

        return (new ColorConverter)->rgb2hex($avgRgb);
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

        $imageMimeType = mime_content_type($imagePath);
        if (!array_key_exists($imageMimeType, $this->mimeTypesMap)) {
            throw new RuntimeException(sprintf('The "%s" mime type not supported.', $imageMimeType));
        }

        return call_user_func($this->mimeTypesMap[$imageMimeType], $imagePath);
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
        $imageWidth = $this->getImageWidth($imageResource);
        $imageHeight = $this->getImageHeight($imageResource);

        for ($x = 0; $x < $imageWidth; $x++) {
            for ($y = 0; $y < $imageHeight; $y++) {
                $callback($imageResource, $x, $y);
            }
        }
    }

    /**
     * Get image width.
     *
     * @param resource $imageResource
     * @return int
     */
    private function getImageWidth($imageResource) : int
    {
        return imagesx($imageResource);
    }

    /**
     * Get image height.
     *
     * @param resource $imageResource
     * @return int
     */
    private function getImageHeight($imageResource) : int
    {
        return imagesy($imageResource);
    }

    /**
     * Get image color for index in RGB format.
     *
     * @param resource $imageResource
     * @param int $x
     * @param int $y
     * @return array
     */
    private function getImageRbgForIndex($imageResource, int $x, int $y) : array
    {
        $rgb = imagecolorsforindex($imageResource, imagecolorat($imageResource, $x, $y));

        return array_values($rgb);
    }

    /**
     * Calculate average color in RGB format.
     *
     * @param array $firstRgb
     * @param array $secondRgb
     * @return array
     */
    private function calculateAvgRgb(array $firstRgb, array $secondRgb) : array
    {
        $avgRgb = [];

        $avgRgb[0] = (int)(($firstRgb[0] + $secondRgb[0]) / 2); // RED
        $avgRgb[1] = (int)(($firstRgb[1] + $secondRgb[1]) / 2); // GREEN
        $avgRgb[2] = (int)(($firstRgb[2] + $secondRgb[2]) / 2); // BLUE

        return $avgRgb;
    }
}
