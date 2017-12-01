<?php

namespace Api\V1\Http\Resources;

use function App\Util\html_purify;
use App\Models\Post;
use App\Util\CastValue;
use Illuminate\Http\Resources\Json\Resource;

/**
 * Class PostPlainResource.
 *
 * @package Api\V1\Http\Resources
 */
class PostPlainResource extends Resource
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
        return [
            'id' => CastValue::toIntOrNull(html_purify($this->resource->id)),
            'created_by_user_id' => CastValue::toIntOrNull(html_purify($this->resource->created_by_user_id)),
            'description' => CastValue::toStringOrNull(html_purify($this->resource->description)),
            'published_at' => CastValue::toStringOrNull(html_purify(optional($this->resource->published_at)->toAtomString())),
            'created_at' => CastValue::toStringOrNull(html_purify(optional($this->resource->created_at)->toAtomString())),
            'updated_at' => CastValue::toStringOrNull(html_purify(optional($this->resource->updated_at)->toAtomString())),
        ];
    }
}
