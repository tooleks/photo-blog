<?php

namespace Api\V1\Presenters;

use Carbon\Carbon;
use Tooleks\Laravel\Presenter\Presenter;

/**
 * Class ExifPresenter.
 *
 * @package Api\V1\Presenters
 */
class ExifPresenter extends Presenter
{
    /**
     * @inheritdoc
     */
    protected function getAttributesMap() : array
    {
        return [
            'manufacturer' => 'data.Make',
            'model' => 'data.Model',
            'exposure_time' => function () {
                $exposureTime = $this->getPresenteeAttribute('data.ExposureTime');
                if ($exposureTime) {
                    list($numerator, $denominator) = explode('/', $exposureTime);
                    $exposureTime = '1/' . $denominator / $numerator;
                }
                return $exposureTime ?? null;
            },
            'aperture' => 'data.COMPUTED.ApertureFNumber',
            'iso' => 'data.ISOSpeedRatings',
            'taken_at' => function () {
                $takenAt = $this->getPresenteeAttribute('data.DateTime');
                return $takenAt !== null ? (string)(new Carbon($takenAt)) : null;
            },
        ];
    }
}
