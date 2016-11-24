<?php

namespace Api\V1\Models\Presenters;

use App\Core\Presenter\EntityPresenter;
use App\Models\DB\Role;
use Exception;

/**
 * Class RolePresenter
 * @property Role role
 * @package Api\V1\Models\Presenters
 */
class RolePresenter extends EntityPresenter
{
    /**
     * RolePresenter constructor.
     *
     * @param Role $entity
     * @throws Exception
     */
    public function __construct($entity)
    {
        parent::__construct($entity);

        if (!($entity instanceof Role)) {
            throw new Exception('Invalid entity type.');
        }
    }

    /**
     * @inheritdoc
     */
    protected function map()
    {
        return [
            'name' => 'name',
        ];
    }
}
