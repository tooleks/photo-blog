<?php

namespace Api\V1\Http\Resources;

use Core\Entities\TagEntity;
use Illuminate\Http\Resources\Json\Resource;
use function App\Util\html_purify;
use function App\Util\to_string;

/**
 * Class TagPlainResource.
 *
 * @package Api\V1\Http\Resources
 */
class TagPlainResource extends Resource
{
    /**
     * @var TagEntity
     */
    public $resource;

    /**
     * @inheritdoc
     */
    public function toArray($request)
    {
        return [
            'value' => to_string(html_purify($this->resource->getValue())),
        ];
    }
}
