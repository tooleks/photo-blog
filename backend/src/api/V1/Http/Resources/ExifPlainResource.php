<?php

namespace Api\V1\Http\Resources;

use function App\Util\fraction_normalize;
use function App\Util\html_purify;
use App\Models\Exif;
use App\Util\Normalizer;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\Resource;

/**
 * Class ExifPlainResource.
 *
 * @package Api\V1\Http\Resources
 */
class ExifPlainResource extends Resource
{
    use Normalizer;

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
            'manufacturer' => $this->normalizeString(html_purify(data_get($this->resource->data, 'Make'))),
            'model' => $this->normalizeString(html_purify(data_get($this->resource->data, 'Model'))),
            'exposure_time' => $this->normalizeString(html_purify(function () {
                return fraction_normalize((string) data_get($this->resource->data, 'ExposureTime'));
            })),
            'aperture' => $this->normalizeString(html_purify(data_get($this->resource->data, 'COMPUTED.ApertureFNumber'))),
            'iso' => $this->normalizeString(html_purify(data_get($this->resource->data, 'ISOSpeedRatings'))),
            'taken_at' => $this->normalizeString(html_purify(function () {
                $takenAt = data_get($this->resource->data, 'DateTimeOriginal');
                return $takenAt ? new Carbon($takenAt) : null;
            })),
        ];
    }
}
