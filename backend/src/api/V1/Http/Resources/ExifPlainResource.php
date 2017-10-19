<?php

namespace Api\V1\Http\Resources;

use function App\Util\fraction_normalize;
use function App\Util\html_purify;
use App\Models\Exif;
use App\Util\CastValue;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\Resource;

/**
 * Class ExifPlainResource.
 *
 * @package Api\V1\Http\Resources
 */
class ExifPlainResource extends Resource
{
    /**
     * @var Exif
     */
    public $resource;

    /**
     * @inheritdoc
     */
    public function toArray($request)
    {
        return [
            'manufacturer' => CastValue::toStringOrNull(html_purify(data_get($this->resource->data, 'Make'))),
            'model' => CastValue::toStringOrNull(html_purify(data_get($this->resource->data, 'Model'))),
            'exposure_time' => CastValue::toStringOrNull(html_purify(function () {
                return fraction_normalize((string) data_get($this->resource->data, 'ExposureTime'));
            })),
            'aperture' => CastValue::toStringOrNull(html_purify(data_get($this->resource->data, 'COMPUTED.ApertureFNumber'))),
            'iso' => CastValue::toStringOrNull(html_purify(data_get($this->resource->data, 'ISOSpeedRatings'))),
            'taken_at' => CastValue::toStringOrNull(html_purify(function () {
                $takenAt = data_get($this->resource->data, 'DateTimeOriginal');
                return $takenAt ? new Carbon($takenAt) : null;
            })),
        ];
    }
}
