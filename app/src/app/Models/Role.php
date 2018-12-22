<?php

namespace App\Models;

use App\Models\Builders\RoleBuilder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Role.
 *
 * @property int id
 * @property string name
 * @package App\Models
 */
class Role extends Model
{
    /**
     * @inheritdoc
     */
    public $timestamps = false;

    /**
     * @inheritdoc
     */
    protected $fillable = [
        'name',
    ];

    /**
     * @inheritdoc
     */
    public function newEloquentBuilder($query): RoleBuilder
    {
        return new RoleBuilder($query);
    }

    /**
     * @inheritdoc
     */
    public function newQuery(): RoleBuilder
    {
        return parent::newQuery();
    }
}
