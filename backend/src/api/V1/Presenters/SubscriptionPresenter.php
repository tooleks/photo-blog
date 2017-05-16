<?php

namespace Api\V1\Presenters;

use Tooleks\Laravel\Presenter\Presenter;

/**
 * Class SubscriptionPresenter.
 *
 * @property string email
 * @property string token
 * @package Api\V1\Presenters
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
