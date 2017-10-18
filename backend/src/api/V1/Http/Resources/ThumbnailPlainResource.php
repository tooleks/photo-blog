<?php

namespace Api\V1\Http\Resources;

use function App\Util\html_purify;
use function App\Util\url_storage;
use App\Models\Thumbnail;
use App\Util\CastsValues;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Storage;

/**
 * Class ThumbnailPlainResource.
 *
 * @package Api\V1\Http\Resources
 */
class ThumbnailPlainResource extends Resource
{
    use CastsValues;

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
            'url' => $this->toStringOrNull(html_purify(function () {
                return url_storage(Storage::url($this->resource->path));
            })),
            'width' => $this->toIntOrNull(html_purify($this->resource->width)),
            'height' => $this->toIntOrNull(html_purify($this->resource->height)),
        ];
    }
}
