<?php

namespace Api\V1\Models\Presenters;

use App\Models\DB\Role;
use Tooleks\Laravel\Presenter\ModelPresenter;

/**
 * Class RolePresenter.
 *
 * @property Role originalModel
 * @package Api\V1\Models\Presenters
 */
class RolePresenter extends ModelPresenter
{
    /**
     * @inheritdoc
     */
    protected function getOriginalModelClass() : string
    {
        return Role::class;
    }

    /**
     * @inheritdoc
     */
    protected function getAttributesMap() : array
    {
        return [
            'name' => 'name',
        ];
    }
}
