<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User
 * @property int id
 * @property int role_id
 * @property string name
 * @property string email
 * @property string password
 * @property string api_token
 * @property string remember_token
 * @property Carbon created_at
 * @property Carbon update_at
 * @property Role role
 * @package App\Models
 */
class User extends Authenticatable
{
    use Notifiable;

    /**
     * @inheritdoc
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'api_token',
    ];

    /**
     * @inheritdoc
     */
    protected $hidden = [
        'password',
        'remember_token',
        'role_id',
    ];

    /**
     * @inheritdoc
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('role', function (Builder $builder) {
            $builder->with('role');
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Check if user is 'Administrator'.
     *
     * @return string
     */
    public function isAdministrator()
    {
        return $this->role->name === Role::NAME_ADMINISTRATOR;
    }

    /**
     * Check if user is 'Customer'.
     *
     * @return string
     */
    public function isCustomer()
    {
        return $this->role->name === Role::NAME_CUSTOMER;
    }
}
