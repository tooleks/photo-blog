<?php

namespace Api\V1\Models\Presenters;

use App\Models\DB\User;
use Tooleks\Laravel\Presenter\ModelPresenter;

/**
 * Class UserPresenter
 * @property User entity
 * @property int id
 * @property string email
 * @package Api\V1\Models\Presenters
 */
class UserPresenter extends ModelPresenter
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
    public function getOriginalModel() : User
    {
        return parent::getOriginalModel();
    }

    /**
     * @inheritdoc
     */
    protected function getAttributesMap() : array
    {
        return [
            'id' => 'id',
            'name' => 'name',
            'email' => 'email',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',
            'role' => 'role',
        ];
    }

    /**
     * @return string
     */
    public function getCreatedAtAttribute()
    {
        return (string)$this->originalModel->created_at ?? null;
    }

    /**
     * @return string
     */
    public function getUpdatedAtAttribute()
    {
        return (string)$this->originalModel->updated_at ?? null;
    }

    /**
     * @return string
     */
    public function getRoleAttribute()
    {
        return new RolePresenter($this->originalModel->role);
    }
}
