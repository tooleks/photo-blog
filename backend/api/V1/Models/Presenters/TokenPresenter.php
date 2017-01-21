<?php

namespace Api\V1\Models\Presenters;

use App\Models\DB\User;
use Tooleks\Laravel\Presenter\ModelPresenter;

/**
 * Class TokenPresenter
 *
 * @property User originalModel
 * @package Api\V1\Models\Presenters
 */
class TokenPresenter extends ModelPresenter
{
    /**
     * @inheritdoc
     */
    protected function getOriginalModelClass() : string
    {
        return User::class;
    }

    /**
     * @inheritdoc
     */
    protected function getAttributesMap() : array
    {
        return [
            'user_id' => 'id',
            'api_token' => 'api_token',
        ];
    }
}
