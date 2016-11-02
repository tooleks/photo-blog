<?php

namespace App\Services\Users;

use App\Services\Users\Events\UserAfterDelete;
use App\Services\Users\Events\UserAfterFind;
use App\Services\Users\Events\UserAfterSave;
use App\Services\Users\Events\UserAfterValidate;
use App\Services\Users\Events\UserBeforeDelete;
use App\Services\Users\Events\UserBeforeFind;
use App\Services\Users\Events\UserBeforeSave;
use App\Services\Users\Events\UserBeforeValidate;
use App\Services\Users\Exceptions\UserException;
use App\Services\Users\Exceptions\UserNotFoundException;
use Exception;
use App\Models\User;
use App\Services\Users\Contracts\UserServiceContract;
use App\Services\Users\Exceptions\UserValidationException;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Validation\Factory as ValidatorFactory;
use Illuminate\Validation\Rule;

/**
 * Class UserService
 * @property ConnectionInterface connection
 * @property ValidatorFactory validator
 * @property string scenario
 * @property Hasher hasher
 * @package App\Services\Users
 */
class UserService implements UserServiceContract
{
    /**
     * UserService constructor.
     *
     * @param ConnectionInterface $connection
     * @param ValidatorFactory $validator
     * @param Hasher $hasher
     */
    public function __construct(ConnectionInterface $connection, ValidatorFactory $validator, Hasher $hasher)
    {
        $this->connection = $connection;
        $this->validator = $validator;
        $this->hasher = $hasher;

        $this->setScenario('default');
    }

    /**
     * @inheritdoc
     */
    public function setScenario(string $scenario)
    {
        $this->scenario = $scenario;

        return $this;
    }

    /**
     * Get validation rules.
     *
     * @param User $user
     * @return array
     */
    private function getValidationRules(User $user) : array
    {
        return [
            'create' => [
                'name' => [
                    'required',
                    'filled',
                    'min:1',
                    'max:255',
                ],
                'email' => [
                    'required',
                    'filled',
                    'email',
                    Rule::unique('users')->ignore($user->id),
                    'min:1',
                    'max:255',
                ],
                'password' => [
                    'required',
                    'filled',
                    'min:1',
                    'max:255',
                ],
            ],
            'update' => [
                'name' => [
                    'filled',
                    'min:1',
                    'max:255',
                ],
                'email' => [
                    'filled',
                    'email',
                    Rule::unique('users')->ignore($user->id),
                    'min:1',
                    'max:255',
                ],
                'password' => [
                    'filled',
                    'min:1',
                    'max:255',
                ],
            ],
            'default' => [],
        ];
    }

    /**
     * Validate attributes.
     *
     * @param User $user
     * @param array $attributes
     * @return Validator
     * @throws UserValidationException
     */
    private function validate(User &$user, array &$attributes) : Validator
    {
        $validator = $this->validator->make($attributes, $this->getValidationRules($user)[$this->scenario]);

        event(new UserBeforeValidate($user, $validator, $attributes, $this->scenario));
        if ($validator->fails()) {
            throw new UserValidationException($validator);
        }
        event(new UserAfterValidate($user, $validator, $attributes, $this->scenario));

        return $validator;
    }

    /**
     * @inheritdoc
     */
    public function create(array $attributes, bool $validate = true)
    {
        $photo = $this->save(new User, $attributes, $validate);

        return $photo;
    }

    public function save(User $user, array $attributes, bool $validate = true) : User
    {
        if ($validate) {
            $this->validate($user, $attributes);
        }

        if (isset($attributes['password']) && strlen($attributes['password'])) {
            $attributes['password'] = $this->hasher->make($attributes['password']);
        }

        $this->connection->beginTransaction();
        try {
            event(new UserBeforeSave($user, $attributes, $this->scenario));
            $user->fill($attributes);
            $user->save();
            event(new UserAfterSave($user, $attributes, $this->scenario));
            $this->connection->commit();
        } catch (Exception $e) {
            $this->connection->rollBack();
            throw new UserException('User was not saved.');
        }

        $photo = $this->getById($user->id);

        return $photo;
    }

    /**
     * @inheritdoc
     */
    public function delete(User $user) : int
    {
        $this->connection->beginTransaction();
        try {
            event(new UserBeforeDelete($user));
            $count = $user->delete();
            event(new UserAfterDelete($user, $count));
            $this->connection->commit();
        } catch (Exception $e) {
            $this->connection->rollBack();
            throw new UserException('Photo was not deleted.');
        }

        return $count;
    }

    /**
     * @inheritdoc
     */
    public function getById(int $id) : User
    {
        $user = new User;

        try {
            event(new UserBeforeFind($user));
            $user = $user->findOrFail($id);
            event(new UserAfterFind($user));
        } catch (Exception $e) {
            throw new UserNotFoundException('User was not found.');
        }

        return $user;
    }

    /**
     * @inheritdoc
     */
    public function getByCredentials(array $credentials) : User
    {
        $user = new User;

        try {
            event(new UserBeforeFind($user, $credentials));
            $user = $user->where('email', $credentials['email'])->firstOrFail();
            if (!$this->hasher->check($credentials['password'], $user->password)) {
                throw new UserException;
            }
            event(new UserAfterFind($user));
        } catch (Exception $e) {
            throw new UserNotFoundException('User was not found.');
        }

        return $user;
    }
}
