<?php

namespace Api\V1\Models\Presenters;

use App\Models\DB\Thumbnail;
use Tooleks\Laravel\Presenter\ModelPresenter;

/**
 * Class TokenPresenter.
 *
 * @property Thumbnail originalModel
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
            'absolute_url' => 'absolute_url',
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
