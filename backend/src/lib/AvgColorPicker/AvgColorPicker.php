<?php

namespace Lib\AvgColorPicker;

use Closure;
use Lib\AvgColorPicker\Contracts\AvgColorPicker as AvgColorPickerContract;
use RuntimeException;

/**
 * Class AvgColorPicker.
 *
 * @package Lib\AvgColorPicker
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

        $this->eachPixelInImage($this->createImageResource($imagePath), function ($imageResource, $x, $y) use (&$avgRgb) {
            $rgb = $this->getImageRbgForIndex($imageResource, $x, $y);
            if (!$avgRgb) return $avgRgb = $rgb;
            $avgRgb[0] = (int)(($avgRgb[0] + $rgb[0]) / 2); // RED
            $avgRgb[1] = (int)(($avgRgb[1] + $rgb[1]) / 2); // GREEN
            $avgRgb[2] = (int)(($avgRgb[2] + $rgb[2]) / 2); // BLUE
        });

        return (new ColorConverter)->rgb2hex($avgRgb);
    }

    /**
     * Apply callback function on each pixel in the image.
     *
     * @param $imageResource
     * @param Closure $callback
     */
    private function eachPixelInImage($imageResource, Closure $callback)
    {
        for ($x = 0; $x < $this->getImageWidth($imageResource); $x++) {
            for ($y = 0; $y < $this->getImageHeight($imageResource); $y++) {
                $callback($imageResource, $x, $y);
            }
        }
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
     * @param int
     * @param int $y
     * @return array
     */
    private function getImageRbgForIndex($imageResource, int $x, int $y) : array
    {
        $index = imagecolorat($imageResource, $x, $y);
        $rgb = imagecolorsforindex($imageResource, $index);

        return array_values($rgb);
    }
}
