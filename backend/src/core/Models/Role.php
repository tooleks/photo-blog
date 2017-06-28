<?php

namespace Core\Models;

use Core\Models\Builders\RoleBuilder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Role.
 *
 * @property string name
 * @package Core\Models
 */
class Role extends Model
{
    public const NAME_CUSTOMER = 'Customer';
    public const NAME_ADMINISTRATOR = 'Administrator';

    /**
     * @inheritdoc
     */
    protected $fillable = [
        'name',
    ];

    /**
     * @inheritdoc
     */
    public $timestamps = false;

    /**
     * @inheritdoc
     */
    public function newEloquentBuilder($query)
    {
        return new RoleBuilder($query);
    }
}
