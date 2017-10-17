<?php

namespace App\Managers\User;

use App\Managers\User\Contracts\UserManager as UserManagerContract;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\ConnectionInterface as DbConnection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class UserManager.
 *
 * @package App\Managers\User
 */
class UserManager implements UserManagerContract
{
    /**
     * @var DbConnection
     */
    private $dbConnection;

    /**
     * @var Hasher
     */
    private $hasher;

    /**
     * @var UserValidator
     */
    private $validator;

    /**
     * UserManager constructor.
     *
     * @param DbConnection $dbConnection
     * @param Hasher $hasher
     * @param UserValidator $validator
     */
    public function __construct(DbConnection $dbConnection, Hasher $hasher, UserValidator $validator)
    {
        $this->dbConnection = $dbConnection;
        $this->hasher = $hasher;
        $this->validator = $validator;
    }

    /**
     * @inheritdoc
     */
    public function getById(int $id): User
    {
        $user = (new User)
            ->newQuery()
            ->findOrFail($id);

        return $user;
    }

    /**
     * @inheritdoc
     */
    public function getByName(string $name): User
    {
        $user = (new User)
            ->newQuery()
            ->whereNameEquals($name)
            ->firstOrFail();

        return $user;
    }

    /**
     * @inheritdoc
     */
    public function getByEmail(string $email): User
    {
        $user = (new User)
            ->newQuery()
            ->whereEmailEquals($email)
            ->firstOrFail();

        return $user;
    }

    /**
     * @inheritdoc
     */
    public function getByCredentials(string $email, string $password): User
    {
        $user = $this->getByEmail($email);

        if (!$this->hasher->check($password, $user->password)) {
            throw new ModelNotFoundException('Invalid user password.');
        }

        return $user;
    }

    /**
     * @inheritdoc
     */
    public function create(array $attributes): User
    {
        // Create a customer user by default.
        if (!isset($attributes['role_id'])) {
            $attributes['role_id'] = (new Role)->newQuery()->whereNameCustomer()->firstOrFail()->id;
        }

        $attributes = $this->validator->validateForCreate($attributes);

        $user = (new User)->fill($attributes);

        $user->password = $this->hasher->make($attributes['password']);

        $this->dbConnection->transaction(function () use ($user) {
            $user->save();
        });

        return $user;
    }

    /**
     * @inheritdoc
     */
    public function save(User $user, array $attributes): void
    {
        $attributes = $this->validator->validateForSave($user, $attributes);

        $user->fill($attributes);

        if (isset($attributes['password'])) {
            $user->password = $this->hasher->make($attributes['password']);
        }

        $this->dbConnection->transaction(function () use ($user) {
            $user->save();
        });
    }

    /**
     * @inheritdoc
     */
    public function delete(User $user): void
    {
        $this->dbConnection->transaction(function () use ($user) {
            $user->delete();
        });
    }
}
