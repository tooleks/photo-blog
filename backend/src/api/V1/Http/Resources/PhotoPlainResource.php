<?php

namespace Api\V1\Http\Resources;

use function App\Util\html_purify;
use App\Models\Photo;
use App\Util\CastValue;
use Illuminate\Http\Resources\Json\Resource;

/**
 * Class PhotoPlainResource.
 *
 * @package Api\V1\Http\Resources
 */
class PhotoPlainResource extends Resource
{
    /**
     * @var Photo
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
            'avg_color' => CastValue::toStringOrNull(html_purify($this->resource->avg_color)),
            'description' => $this->when($this->resource->isPublished(), function () {
                return CastValue::toStringOrNull(html_purify($this->resource->description));
            }),
            'created_at' => CastValue::toStringOrNull(html_purify($this->resource->created_at)),
            'updated_at' => CastValue::toStringOrNull(html_purify($this->resource->updated_at)),
        ];
    }
}
