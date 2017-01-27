<?php

namespace Api\V1\Models\Presenters;

use App\Models\DB\User;
use Tooleks\Laravel\Presenter\ModelPresenter;

/**
 * Class UserPresenter.
 *
 * @property User originalModel
 * @property int id
 * @property string name
 * @property string email
 * @property string created_at
 * @property string updated_at
 * @property RolePresenter role
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
    protected function getAttributesMap() : array
    {
        return [
            // 'model_presenter_attribute_name' => 'original_model_attribute_name'
            'id' => 'id',
            'name' => 'name',
            'email' => 'email',
            'created_at' => null,
            'updated_at' => null,
            'role' => null,
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
