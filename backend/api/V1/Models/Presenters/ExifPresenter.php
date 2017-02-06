<?php

namespace Api\V1\Models\Presenters;

use Carbon\Carbon;
use Tooleks\Laravel\Presenter\Presenter;

/**
 * Class ExifPresenter.
 *
 * @package Api\V1\Models\Presenters
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
            'exposure_time' => function () : string {
                if ($this->getPresenteeAttribute('data.ExposureTime')) {
                    list($numerator, $denominator) = explode('/', $this->getPresenteeAttribute('data.ExposureTime'));
                    return '1/' . $denominator / $numerator;
                } else {
                    return null;
                }
            },
            'aperture' => 'data.COMPUTED.ApertureFNumber',
            'iso' => 'data.ISOSpeedRatings',
            'taken_at' => function () : string {
                return new Carbon($this->getPresenteeAttribute('data.DateTime'));
            },
        ];
    }
}
