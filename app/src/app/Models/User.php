<?php

namespace App\Models;

use App\Models\Builders\UserBuilder;
use Carbon\Carbon;
use Core\Entities\UserEntity;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Passport\HasApiTokens;

/**
 * Class User.
 *
 * @property int id
 * @property int role_id
 * @property string name
 * @property string email
 * @property string password
 * @property string remember_token
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Role role
 * @property Collection photos
 * @package App\Models
 */
class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * @inheritdoc
     */
    protected $fillable = [
        'role_id',
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

        static::deleting(function (self $user) {
            optional($user->photos)->each(function (Photo $photo) {
                $photo->delete();
            });
        });
    }

    /**
     * @inheritdoc
     */
    public function newEloquentBuilder($query): UserBuilder
    {
        return new UserBuilder($query);
    }

    /**
     * @inheritdoc
     */
    public function newQuery(): UserBuilder
    {
        return parent::newQuery();
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

    /**
     * @return UserEntity
     */
    public function toEntity(): UserEntity
    {
        return new UserEntity([
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'password_hash' => $this->password,
            'role' => optional($this->role)->name,
            'created_at' => $this->created_at->toAtomString(),
            'updated_at' => $this->updated_at->toAtomString(),
        ]);
    }
}
