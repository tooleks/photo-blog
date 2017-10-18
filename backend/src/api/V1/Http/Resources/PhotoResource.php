<?php

namespace Api\V1\Http\Resources;

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
            'exif' => $this->normalizeClassObject($this->resource->exif, ExifPlainResource::class),
            'thumbnails' => [
                'medium' => $this->normalizeClassObject($this->resource->thumbnails->get(0), ThumbnailPlainResource::class),
                'large' => $this->normalizeClassObject($this->resource->thumbnails->get(1), ThumbnailPlainResource::class),
            ],
            'tags' => $this->when($this->resource->isPublished(), function () {
                return collect($this->resource->tags)->map(function ($tag) {
                    return $this->normalizeClassObject($tag, TagPlainResource::class);
                });
            }),
        ]);
    }
}
