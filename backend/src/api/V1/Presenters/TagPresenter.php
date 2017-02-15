<?php

namespace Api\V1\Presenters;

use Tooleks\Laravel\Presenter\Presenter;

/**
 * Class TagPresenter.
 *
 * @property string text
 * @package Api\V1\Presenters
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
