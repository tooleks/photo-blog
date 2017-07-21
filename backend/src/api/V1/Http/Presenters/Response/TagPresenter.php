<?php

namespace Api\V1\Http\Presenters\Response;

use function App\xss_protect;
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
                return xss_protect($this->getWrappedModelAttribute('value'));
            },
        ];
    }
}
