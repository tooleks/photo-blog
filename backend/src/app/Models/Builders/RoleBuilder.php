<?php

namespace App\Models\Builders;

use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

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
    private $rolesTable;

    /**
     * RoleBuilder constructor.
     *
     * @param QueryBuilder $query
     */
    public function __construct(QueryBuilder $query)
    {
        parent::__construct($query);

        $this->rolesTable = (new Role)->getTable();
    }

    /**
     * @return $this
     */
    public function whereNameCustomer()
    {
        return $this->where("{$this->rolesTable}.name", Role::NAME_CUSTOMER);
    }

    /**
     * @return $this
     */
    public function whereNameAdministrator()
    {
        return $this->where("{$this->rolesTable}.name", Role::NAME_ADMINISTRATOR);
    }
}
