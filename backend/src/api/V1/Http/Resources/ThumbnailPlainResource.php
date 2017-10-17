<?php

namespace Api\V1\Http\Resources;

use function App\Util\html_purify;
use function App\Util\url_storage;
use App\Models\Thumbnail;
use App\Util\Normalizer;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Storage;

/**
 * Class ThumbnailPlainResource.
 *
 * @package Api\V1\Http\Resources
 */
class ThumbnailPlainResource extends Resource
{
    use Normalizer;

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
            'url' => $this->normalizeString(html_purify(function () {
                return url_storage(Storage::url($this->resource->path));
            })),
            'width' => $this->normalizeInt(html_purify($this->resource->width)),
            'height' => $this->normalizeInt(html_purify($this->resource->height)),
        ];
    }
}
