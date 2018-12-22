<?php

namespace App\Models\Builders;

use App\Models\Tables\Constant;
use Core\Entities\UserEntity;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class RoleBuilder.
 *
 * @package App\Models\Builders
 */
class RoleBuilder extends Builder
{
    /**
     * @var string
     */
    private $rolesTable = Constant::TABLE_ROLES;

    /**
     * @return $this
     */
    public function whereNameCustomer()
    {
        return $this->where("{$this->rolesTable}.name", UserEntity::ROLE_CUSTOMER);
    }

    /**
     * @return $this
     */
    public function whereNameAdministrator()
    {
        return $this->where("{$this->rolesTable}.name", UserEntity::ROLE_ADMINISTRATOR);
    }
}
