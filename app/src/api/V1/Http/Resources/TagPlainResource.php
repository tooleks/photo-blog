<?php

namespace Api\V1\Http\Resources;

use function App\Util\html_purify;
use App\Models\Tag;
use App\Util\CastValue;
use Illuminate\Http\Resources\Json\Resource;

/**
 * Class TagPlainResource.
 *
 * @package Api\V1\Http\Resources
 */
class TagPlainResource extends Resource
{
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
            'value' => CastValue::toStringOrNull(html_purify($this->resource->value)),
        ];
    }
}
