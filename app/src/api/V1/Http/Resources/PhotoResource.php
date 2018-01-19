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
            'exif' => CastValue::toClassObjectOrNull($this->resource->exif, ExifPlainResource::class),
            'thumbnails' => [
                'medium' => CastValue::toClassObjectOrNull($this->resource->thumbnails->get(0), ThumbnailPlainResource::class),
                'large' => CastValue::toClassObjectOrNull($this->resource->thumbnails->get(1), ThumbnailPlainResource::class),
            ],
        ]);
    }
}
