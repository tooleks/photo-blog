<?php

namespace Core\Models;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User.
 *
 * @property int id
 * @property int role_id
 * @property string name
 * @property string email
 * @property string password
 * @property string api_token
 * @property string remember_token
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Role role
 * @package Core\Models
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
    ];

    /**
     * @inheritdoc
     */
    protected $with = [
        'role',
    ];

    /**
     * @inheritdoc
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function (User $user) {
            $user->photos()->delete();
        });
    }

    /**
     * Set password.
     *
     * @param string $password
     *
     * @return $this
     */
    public function setPassword(string $password)
    {
        $this->attributes['password'] = $password;

        return $this;
    }

    /**
     * Generate API token.
     *
     * @return $this
     */
    public function generateApiToken()
    {
        $this->api_token = str_random(64);

        return $this;
    }

    /**
     * Set customer role.
     *
     * @return $this
     */
    public function setCustomerRoleId()
    {
        $this->role_id = Role::whereNameCustomer()->first()->id;

        return $this;
    }

    /**
     * Set administrator role.
     *
     * @return $this
     */
    public function setAdministratorRoleId()
    {
        $this->role_id = Role::whereNameAdministrator()->first()->id;

        return $this;
    }

    /**
     * Check if user is 'Administrator'.
     *
     * @return bool
     */
    public function isAdministrator()
    {
        return $this->role ? $this->role->name === Role::NAME_ADMINISTRATOR : false;
    }

    /**
     * Check if user is 'Customer'.
     *
     * @return bool
     */
    public function isCustomer()
    {
        return $this->role ? $this->role->name === Role::NAME_CUSTOMER : false;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function photos()
    {
        return $this->hasMany(Photo::class, 'created_by_user_id');
    }
}
