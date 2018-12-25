<?php

namespace Api\V1\Http\Resources;

use function App\Util\html_purify;
use function App\Util\to_object;
use function App\Util\to_string;

/**
 * Class PhotoResource.
 *
 * @package Api\V1\Http\Resources
 */
class PhotoResource extends PhotoPlainResource
{
    /**
     * @inheritdoc
     */
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'location' => to_object($this->resource->getLocation(), LocationPlainResource::class),
            'exif' => [
                'manufacturer' => to_string(html_purify($this->resource->getMetadata()->getManufacturer())),
                'model' => to_string(html_purify($this->resource->getMetadata()->getModel())),
                'exposure_time' => to_string(html_purify($this->resource->getMetadata()->getExposureTime())),
                'aperture' => to_string(html_purify($this->resource->getMetadata()->getAperture())),
                'focal_length' => to_string(html_purify($this->resource->getMetadata()->getFocalLength())),
                'focal_length_in_35_mm' => to_string(html_purify($this->resource->getMetadata()->getFocalLengthIn35mm())),
                'iso' => to_string(html_purify($this->resource->getMetadata()->getIso())),
                'taken_at' => to_string(html_purify(optional($this->resource->getMetadata()->getTakenAt())->toAtomString())),
                'software' => to_string(html_purify($this->resource->getMetadata()->getSoftware())),
            ],
            'thumbnails' => [
                'medium' => to_object($this->resource->getThumbnails()->offsetGet(0), ThumbnailPlainResource::class),
                'large' => to_object($this->resource->getThumbnails()->offsetGet(1), ThumbnailPlainResource::class),
            ],
        ]);
    }
}
