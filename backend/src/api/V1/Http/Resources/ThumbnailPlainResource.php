<?php

namespace Api\V1\Http\Resources;

use function App\Util\html_purify;
use function App\Util\url_storage;
use App\Models\Thumbnail;
use App\Util\CastValue;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Storage;

/**
 * Class ThumbnailPlainResource.
 *
 * @package Api\V1\Http\Resources
 */
class ThumbnailPlainResource extends Resource
{
    /**
     * @var Thumbnail
     */
    public $resource;

    /**
     * @inheritdoc
     */
    public function toArray($request)
    {
        return [
            'url' => CastValue::toStringOrNull(html_purify(function () {
                return url_storage(Storage::url($this->resource->path));
            })),
            'width' => CastValue::toIntOrNull(html_purify($this->resource->width)),
            'height' => CastValue::toIntOrNull(html_purify($this->resource->height)),
        ];
    }
}
