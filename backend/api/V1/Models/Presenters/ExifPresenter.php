<?php

namespace Api\V1\Models\Presenters;

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
            // 'presenter_attribute_name' => 'presentee_attribute_name'
            'manufacturer' => 'data.Make',
            'model' => 'data.Model',
            'exposure_time' => 'data.ExposureTime',
            'aperture' => 'data.COMPUTED.ApertureFNumber',
            'iso' => 'data.ISOSpeedRatings',
            'taken_at' => 'data.DateTime',
        ];
    }
}
