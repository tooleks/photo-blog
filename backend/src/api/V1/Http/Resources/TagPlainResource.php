<?php

namespace Api\V1\Http\Resources;

use function App\Util\html_purify;
use App\Models\Tag;
use App\Util\CastsValues;
use Illuminate\Http\Resources\Json\Resource;

/**
 * Class TagPlainResource.
 *
 * @package Api\V1\Http\Resources
 */
class TagPlainResource extends Resource
{
    use CastsValues;

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
            'value' => $this->toStringOrNull(html_purify($this->resource->value)),
        ];
    }
}
