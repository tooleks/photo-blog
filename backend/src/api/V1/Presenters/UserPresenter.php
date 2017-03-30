<?php

namespace Api\V1\Presenters;

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
 * @package Api\V1\Presenters
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
            'created_at' => function () {
                $createdAt = $this->getPresenteeAttribute('created_at');
                return (string)$createdAt?? null;
            },
            'updated_at' => function () {
                $updatedAt = $this->getPresenteeAttribute('updated_at');
                return (string)$updatedAt ?? null;
            },
            'role' => 'role.name',
        ];
    }
}
