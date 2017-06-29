<?php

namespace Api\V1\Http\Presenters\Response;

use Tooleks\Laravel\Presenter\Presenter;

/**
 * Class TokenPresenter.
 *
 * @property int user_id
 * @property string api_token
 * @package Api\V1\Http\Presenters\Response
 */
class TokenPresenter extends Presenter
{
    /**
     * @inheritdoc
     */
    protected function getAttributesMap(): array
    {
        return [
            'user_id' => 'id',
            'api_token' => 'api_token',
        ];
    }
}
