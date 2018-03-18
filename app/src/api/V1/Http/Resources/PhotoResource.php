<?php

namespace Api\V1\Http\Resources;

use App\Util\CastValue;

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
            'location' => CastValue::toClassObjectOrNull($this->resource->location, LocationPlainResource::class),
            'exif' => CastValue::toClassObjectOrNull($this->resource->exif, ExifPlainResource::class),
            'thumbnails' => [
                'medium' => CastValue::toClassObjectOrNull(data_get($this->resource, 'thumbnails.0'), ThumbnailPlainResource::class),
                'large' => CastValue::toClassObjectOrNull(data_get($this->resource, 'thumbnails.1'), ThumbnailPlainResource::class),
            ],
        ]);
    }
}
