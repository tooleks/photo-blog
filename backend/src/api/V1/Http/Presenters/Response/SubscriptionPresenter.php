<?php

namespace Api\V1\Http\Presenters\Response;

use Tooleks\Laravel\Presenter\Presenter;

/**
 * Class SubscriptionPresenter.
 *
 * @property string email
 * @property string token
 * @package Api\V1\Http\Presenters\Response
 */
class SubscriptionPresenter extends Presenter
{
    /**
     * @inheritdoc
     */
    protected function getAttributesMap(): array
    {
        return [
            'email' => 'email',
            'token' => 'token',
        ];
    }
}
