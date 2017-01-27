<?php

namespace Api\V1\Models\Presenters;

use App\Models\DB\Exif;
use Tooleks\Laravel\Presenter\ModelPresenter;

/**
 * Class ExifPresenter.
 *
 * @property Exif originalModel
 * @package Api\V1\Models\Presenters
 */
class ExifPresenter extends ModelPresenter
{
    /**
     * @inheritdoc
     */
    protected function getOriginalModelClass() : string
    {
        return Exif::class;
    }

    /**
     * @inheritdoc
     */
    protected function getAttributesMap() : array
    {
        return [
            // 'model_presenter_attribute_name' => 'original_model_attribute_name'
            'manufacturer' => null,
            'model' => null,
            'exposure' => null,
            'aperture' => null,
            'iso' => null,
            'taken_at' => null,
        ];
    }

    /**
     * @return string|null
     */
    public function getManufacturerAttribute()
    {
        return $this->originalModel->data['Make'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getModelAttribute()
    {
        return $this->originalModel->data['Model'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getExposureAttribute()
    {
        return $this->originalModel->data['ExposureTime'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getApertureAttribute()
    {
        return $this->originalModel->data['COMPUTED']['ApertureFNumber'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getIsoAttribute()
    {
        return $this->originalModel->data['ISOSpeedRatings'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getTakenAtAttribute()
    {
        return $this->originalModel->data['DateTime'] ?? null;
    }
}
