<?php

namespace Api\V1\Http\Resources;

use Core\Entities\LocationEntity;
use Illuminate\Http\Resources\Json\Resource;
use function App\Util\html_purify;
use function App\Util\to_float;

/**
 * Class LocationPlainResource.
 *
 * @package Api\V1\Http\Resources
 */
class LocationPlainResource extends Resource
{
    /**
     * @var LocationEntity
     */
    public $resource;

    /**
     * @inheritdoc
     */
    public function toArray($request)
    {
        return [
            'latitude' => to_float(html_purify($this->resource->getCoordinates()->getLatitude())),
            'longitude' => to_float(html_purify($this->resource->getCoordinates()->getLongitude())),
        ];
    }
}
