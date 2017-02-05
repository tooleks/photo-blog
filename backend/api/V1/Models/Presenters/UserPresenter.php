<?php

namespace Api\V1\Models\Presenters;

use Tooleks\Laravel\Presenter\Presenter;

/**
 * Class UserPresenter.
 *
 * @property int id
 * @property string name
 * @property string email
 * @property string created_at
 * @property string updated_at
 * @property string role
 * @package Api\V1\Models\Presenters
 */
class UserPresenter extends Presenter
{
    /**
     * @inheritdoc
     */
    protected function getAttributesMap() : array
    {
        return [
            'id' => 'id',
            'name' => 'name',
            'email' => 'email',
            'created_at' => function () : string {
                return $this->getPresenteeAttribute('created_at') ?? null;
            },
            'updated_at' => function () : string {
                return $this->getPresenteeAttribute('updated_at') ?? null;
            },
            'role' => 'role.name',
        ];
    }
}
