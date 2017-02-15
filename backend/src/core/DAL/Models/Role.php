<?php

namespace Core\DAL\Models;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Role.
 *
 * @property string name
 * @method static Role administrator()
 * @method static Role customer()
 * @package Core\DAL\Models
 */
class Role extends Model
{
    const NAME_ADMINISTRATOR = 'Administrator';
    const NAME_CUSTOMER = 'Customer';

    /**
     * @inheritdoc
     */
    protected $fillable = ['name'];

    /**
     * @inheritdoc
     */
    public $timestamps = false;

    /**
     * Scope a query to only include 'Administrator' role name.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeAdministrator($query)
    {
        return $query->where('name', static::NAME_ADMINISTRATOR);
    }

    /**
     * Scope a query to only include 'Customer' role name.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeCustomer($query)
    {
        return $query->where('name', static::NAME_CUSTOMER);
    }
}
