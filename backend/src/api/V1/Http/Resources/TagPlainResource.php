<?php

namespace Api\V1\Http\Resources;

use function App\Util\html_purify;
use App\Models\Tag;
use App\Util\Normalizer;
use Illuminate\Http\Resources\Json\Resource;

/**
 * Class TagPlainResource.
 *
 * @package Api\V1\Http\Resources
 */
class TagPlainResource extends Resource
{
    use Normalizer;

    /**
     * @var Tag
     */
    public $resource;

    /**
     * @inheritdoc
     */
    public function toArray($request)
    {
        return [
            'value' => $this->normalizeString(html_purify($this->resource->value)),
        ];
    }
}
