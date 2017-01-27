<?php

namespace Api\V1\Models\Presenters;

use App\Models\DB\Thumbnail;
use Tooleks\Laravel\Presenter\ModelPresenter;

/**
 * Class TokenPresenter.
 *
 * @property Thumbnail originalModel
 * @property string absolute_url
 * @property int width
 * @property int height
 * @package Api\V1\Models\Presenters
 */
class ThumbnailPresenter extends ModelPresenter
{
    /**
     * @inheritdoc
     */
    protected function getOriginalModelClass() : string
    {
        return Thumbnail::class;
    }

    /**
     * @inheritdoc
     */
    protected function getAttributesMap() : array
    {
        return [
            // 'model_presenter_attribute_name' => 'original_model_attribute_name'
            'absolute_url' => null,
            'width' => 'width',
            'height' => 'height',
        ];
    }

    /**
     * @return string
     */
    public function getAbsoluteUrlAttribute()
    {
        return $this->originalModel->relative_url ? url(config('app.url')) . $this->originalModel->relative_url : '';
    }
}
