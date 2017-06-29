<?php

namespace Api\V1\Http\Presenters\Response;

use Carbon\Carbon;
use Tooleks\Laravel\Presenter\Presenter;

/**
 * Class ExifPresenter.
 *
 * @property string manufacturer
 * @property string model
 * @property string exposure_time
 * @property string aperture
 * @property string iso
 * @property string taken_at
 * @package Api\V1\Http\Presenters\Response
 */
class ExifPresenter extends Presenter
{
    /**
     * @inheritdoc
     */
    protected function getAttributesMap(): array
    {
        return [
            'manufacturer' => 'data.Make',
            'model' => 'data.Model',
            'exposure_time' => function (): ?string {
                $exposureTime = $this->getWrappedModelAttribute('data.ExposureTime');
                if ($exposureTime) {
                    [$numerator, $denominator] = explode('/', $exposureTime);
                    $exposureTime = '1/' . (int) ($denominator / $numerator);
                }
                return $exposureTime ?? null;
            },
            'aperture' => 'data.COMPUTED.ApertureFNumber',
            'iso' => 'data.ISOSpeedRatings',
            'taken_at' => function (): ?string {
                $takenAt = $this->getWrappedModelAttribute('data.DateTimeOriginal');
                return $takenAt ? new Carbon($takenAt) : null;
            },
        ];
    }
}
