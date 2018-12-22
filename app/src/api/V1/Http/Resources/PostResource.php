<?php

namespace Api\V1\Http\Resources;

use Core\Entities\PostEntity;
use Core\Entities\TagEntity;
use function App\Util\to_object;

/**
 * Class PostResource.
 *
 * @package Api\V1\Http\Resources
 */
class PostResource extends PostPlainResource
{
    /**
     * @var PostEntity
     */
    public $resource;

    /**
     * @inheritdoc
     */
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'photo' => to_object($this->resource->getPhoto(), PhotoResource::class),
            'tags' => collect($this->resource->getTags())->map(function (TagEntity $tag) {
                return to_object($tag, TagPlainResource::class);
            }),
        ]);
    }
}
