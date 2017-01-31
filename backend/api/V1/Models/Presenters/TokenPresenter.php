<?php

namespace Api\V1\Models\Presenters;

use Tooleks\Laravel\Presenter\Presenter;

/**
 * Class TokenPresenter.
 *
 * @property int user_id
 * @property string api_token
 * @package Api\V1\Models\Presenters
 */
class TokenPresenter extends Presenter
{
    /**
     * @inheritdoc
     */
    protected function getAttributesMap() : array
    {
        return [
            // 'presenter_attribute_name' => 'presentee_attribute_name'
            'user_id' => 'id',
            'api_token' => 'api_token',
        ];
    }
}
