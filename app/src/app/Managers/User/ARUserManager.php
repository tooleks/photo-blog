<?php

namespace App\Managers\User;

use App\Models\Role;
use App\Models\User;
use Core\Contracts\UserManager;
use Core\Entities\UserEntity;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\ConnectionInterface as Database;

/**
 * Class ARUserManager.
 *
 * @package App\Managers\User
 */
class ARUserManager implements UserManager
{
    /**
     * @var Database
     */
    private $database;

    /**
     * @var Hasher
     */
    private $hasher;

    /**
     * @var UserValidator
     */
    private $validator;

    /**
     * ARUserManager constructor.
     *
     * @param Database $database
     * @param Hasher $hasher
     * @param UserValidator $validator
     */
    public function __construct(Database $database, Hasher $hasher, UserValidator $validator)
    {
        $this->database = $database;
        $this->hasher = $hasher;
        $this->validator = $validator;
    }

    /**
     * @inheritdoc
     */
    public function create(array $attributes): UserEntity
    {
        // Create a customer user by default.
        if (!isset($attributes['role_id'])) {
            $attributes['role_id'] = (new Role)->newQuery()->whereNameCustomer()->firstOrFail()->id;
        }

        $attributes = $this->validator->validateForCreate($attributes);

        $user = (new User)->fill($attributes);

        $user->password = $this->hasher->make($attributes['password']);

        $this->database->transaction(function () use ($user) {
            $user->save();
        });

        return $user->toEntity();
    }

    /**
     * @inheritdoc
     */
    public function updateById(int $id, array $attributes): UserEntity
    {
        $user = (new User)->newQuery()->findOrFail($id);

        $attributes = $this->validator->validateForSave($user, $attributes);

        $user->fill($attributes);

        if (isset($attributes['password'])) {
            $user->password = $this->hasher->make($attributes['password']);
        }

        $this->database->transaction(function () use ($user) {
            $user->save();
        });

        return $user->toEntity();
    }

    /**
     * @inheritdoc
     */
    public function getById(int $id): UserEntity
    {
        $user = (new User)
            ->newQuery()
            ->findOrFail($id);

        return $user->toEntity();
    }

    /**
     * @inheritdoc
     */
    public function getByName(string $name): UserEntity
    {
        $user = (new User)
            ->newQuery()
            ->whereNameEquals($name)
            ->firstOrFail();

        return $user->toEntity();
    }

    /**
     * @inheritdoc
     */
    public function deleteById(int $id): UserEntity
    {
        $user = (new User)
            ->newQuery()
            ->findOrFail($id);

        $this->database->transaction(function () use ($user) {
            $user->delete();
        });

        return $user->toEntity();
    }
}
