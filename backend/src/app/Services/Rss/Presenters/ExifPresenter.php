<?php

namespace App\Services\Rss\Presenters;

use function App\xss_protect;
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
 * @package App\Services\Rss\Presenters
 */
class ExifPresenter extends Presenter
{
    /**
     * @inheritdoc
     */
    protected function getAttributesMap(): array
    {
        return [
            'manufacturer' => function (): ?string {
                $manufacturer = $this->getWrappedModelAttribute('data.Make');
                return is_null($manufacturer) ? $manufacturer : xss_protect($manufacturer);
            },
            'model' => function (): ?string {
                $model = $this->getWrappedModelAttribute('data.Model');
                return is_null($model) ? $model : xss_protect($model);
            },
            'exposure_time' => function (): ?string {
                $exposureTime = $this->getWrappedModelAttribute('data.ExposureTime');
                if ($exposureTime) {
                    [$numerator, $denominator] = explode('/', $exposureTime);
                    $exposureTime = '1/' . (int) ($denominator / $numerator);
                }
                return is_null($exposureTime) ? $exposureTime : xss_protect($exposureTime);
            },
            'aperture' => function (): ?string {
                $aperture = $this->getWrappedModelAttribute('data.COMPUTED.ApertureFNumber');
                return is_null($aperture) ? $aperture : xss_protect($aperture);
            },
            'iso' => function (): ?string {
                $iso = $this->getWrappedModelAttribute('data.ISOSpeedRatings');
                return is_null($iso) ? $iso : xss_protect($iso);
            },
            'taken_at' => function (): ?string {
                $takenAt = $this->getWrappedModelAttribute('data.DateTimeOriginal');
                return $takenAt ? new Carbon($takenAt) : null;
            },
        ];
    }
}
