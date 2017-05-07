<?php

namespace Core\Models\Builders;

use Core\Models\Role;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class RoleBuilder.
 *
 * @package Core\Models\Builders
 */
class RoleBuilder extends Builder
{
    /**
     * Scope where model name is Role::NAME_CUSTOMER.
     *
     * @see Role::NAME_CUSTOMER
     *
     * @return $this
     */
    public function whereNameCustomer()
    {
        return $this->where('name', Role::NAME_CUSTOMER);
    }

    /**
     * Scope where model name is Role::NAME_ADMINISTRATOR.
     *
     * @see Role::NAME_ADMINISTRATOR
     *
     * @return $this
     */
    public function whereNameAdministrator()
    {
        return $this->where('name', Role::NAME_ADMINISTRATOR);
    }
}
