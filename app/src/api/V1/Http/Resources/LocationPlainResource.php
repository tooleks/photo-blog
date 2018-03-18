<?php

namespace Api\V1\Http\Resources;

use function App\Util\html_purify;
use App\Models\Location;
use App\Util\CastValue;
use Illuminate\Http\Resources\Json\Resource;

/**
 * Class LocationPlainResource.
 *
 * @package Api\V1\Http\Resources
 */
class LocationPlainResource extends Resource
{
    /**
     * @var Location
     */
    public $resource;

    /**
     * @inheritdoc
     */
    public function toArray($request)
    {
        return [
            'latitude' => CastValue::toFloatOrNull(html_purify($this->resource->coordinates->getLatitude())),
            'longitude' => CastValue::toFloatOrNull(html_purify($this->resource->coordinates->getLongitude())),
        ];
    }
}
