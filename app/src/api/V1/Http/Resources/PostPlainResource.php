<?php

namespace Api\V1\Http\Resources;

use Core\Entities\PostEntity;
use Illuminate\Http\Resources\Json\Resource;
use function App\Util\html_purify;
use function App\Util\to_bool;
use function App\Util\to_int;
use function App\Util\to_string;

/**
 * Class PostPlainResource.
 *
 * @package Api\V1\Http\Resources
 */
class PostPlainResource extends Resource
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
        return [
            'id' => to_int(html_purify($this->resource->getId())),
            'created_by_user_id' => to_int(html_purify($this->resource->getCreatedByUserId())),
            'is_published' => to_bool(html_purify($this->resource->isPublished())),
            'description' => to_string(html_purify($this->resource->getDescription())),
            'published_at' => to_string(html_purify(optional($this->resource->getPublishedAt())->toAtomString())),
            'created_at' => to_string(html_purify($this->resource->getCreatedAt()->toAtomString())),
            'updated_at' => to_string(html_purify($this->resource->getUpdatedAt()->toAtomString())),
        ];
    }
}
