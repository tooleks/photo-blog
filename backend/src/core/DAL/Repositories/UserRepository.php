<?php

namespace Core\DAL\Repositories;

use Core\DAL\Models\User;
use Core\DAL\Repositories\Contracts\UserRepository as UserRepositoryContract;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator as ValidatorFactory;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/**
 * Class UserRepository.
 *
 * @property Hasher hasher
 * @package Core\DAL\Repositories
 */
class UserRepository implements UserRepositoryContract
{
    /**
     * UserRepository constructor.
     *
     * @param Hasher $hasher
     */
    public function __construct(Hasher $hasher)
    {
        $this->hasher = $hasher;
    }

    /**
     * @inheritdoc
     */
    public function getUserByCredentials(string $email, string $password) : User
    {
        $user = User::whereEmail($email)->first();

        if (is_null($user)) {
            throw new ModelNotFoundException('User not found.');
        }

        if (!$this->hasher->check($password, $user->password)) {
            throw new ModelNotFoundException('Invalid user password.');
        }

        return $user;
    }

    /**
     * @inheritdoc
     */
    public function getUserById(int $id) : User
    {
        $user = User::find($id);

        if (is_null($user)) {
            throw new ModelNotFoundException('User not found.');
        }

        return $user;
    }

    /**
     * @inheritdoc
     */
    public function saveUser(User $user, array $attributes = [])
    {
        $validator = ValidatorFactory::make(['email' => $user->email], [
            'email' => $user->exists
                ? Rule::unique('users')->ignore($user->getOriginal('email'), 'email')
                : Rule::unique('users'),
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $user->fill($attributes);

        if ($user->isDirty('password')) {
            $user->setPassword($this->hasher->make($user->password));
        }

        $user->saveOrFail();
    }

    /**
     * @inheritdoc
     */
    public function deleteUser(User $user) : bool
    {
        return $user->delete();
    }
}
