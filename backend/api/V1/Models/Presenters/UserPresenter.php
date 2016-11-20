<?php

namespace Api\V1\Models\Presenters;

use App\Core\Presenter\EntityPresenter;
use App\Models\DB\User;
use Exception;

/**
 * Class UserPresenter
 * @property User entity
 * @property int id
 * @property string email
 * @package Api\V1\Models\Presenters
 */
class UserPresenter extends EntityPresenter
{
    /**
     * UserPresenter constructor.
     *
     * @param User $entity
     * @throws Exception
     */
    public function __construct($entity)
    {
        parent::__construct($entity);

        if (!($entity instanceof User)) {
            throw new Exception('Invalid entity type.');
        }
    }

    /**
     * @inheritdoc
     */
    protected function map()
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
    public function createdAt()
    {
        return (string)$this->entity->created_at ?? null;
    }

    /**
     * @return string
     */
    public function updatedAt()
    {
        return (string)$this->entity->updated_at ?? null;
    }

    /**
     * @return string
     */
    public function role()
    {
        return new RolePresenter($this->entity->role);
    }
}
