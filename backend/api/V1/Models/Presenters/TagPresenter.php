<?php

namespace Api\V1\Models\Presenters;

use Tooleks\Laravel\Presenter\Presenter;

/**
 * Class TagPresenter.
 *
 * @property string text
 * @package Api\V1\Models\Presenters
 */
class TagPresenter extends Presenter
{
    /**
     * @inheritdoc
     */
    protected function getAttributesMap() : array
    {
        return [
            'text' => 'text',
        ];
    }
}
