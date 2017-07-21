<?php

namespace Api\V1\Http\Presenters\Response;

use Tooleks\Laravel\Presenter\Presenter;

/**
 * Class TagPresenter.
 *
 * @property string value
 * @package Api\V1\Http\Presenters\Response
 */
class TagPresenter extends Presenter
{
    /**
     * @inheritdoc
     */
    protected function getAttributesMap(): array
    {
        return [
            'value' => function (): ?string {
                return htmlspecialchars($this->getWrappedModelAttribute('value'), ENT_QUOTES);
            },
        ];
    }
}
