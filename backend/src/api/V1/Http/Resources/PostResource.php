<?php

namespace Api\V1\Http\Resources;

use App\Models\Post;
use App\Models\Tag;
use App\Util\CastValue;

/**
 * Class PostResource.
 *
 * @package Api\V1\Http\Resources
 */
class PostResource extends PostPlainResource
{
    /**
     * @var Post
     */
    public $resource;

    /**
     * @inheritdoc
     */
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'photo' => CastValue::toClassObjectOrNull($this->resource->photo, PhotoResource::class),
            'tags' => collect($this->resource->tags)->map(function (Tag $tag) {
                return CastValue::toClassObjectOrNull($tag, TagPlainResource::class);
            }),
        ]);
    }
}
