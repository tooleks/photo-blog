<?php

namespace Api\V1\Models\Presenters;

use App\Models\DB\Tag;
use Tooleks\Laravel\Presenter\ModelPresenter;

/**
 * Class TagPresenter.
 *
 * @property Tag originalModel
 * @property string text
 * @package Api\V1\Models\Presenters
 */
class TagPresenter extends ModelPresenter
{
    /**
     * @inheritdoc
     */
    protected function getOriginalModelClass() : string
    {
        return Tag::class;
    }

    /**
     * @inheritdoc
     */
    protected function getAttributesMap() : array
    {
        return [
            // 'model_presenter_attribute_name' => 'original_model_attribute_name'
            'text' => 'text',
        ];
    }
}
