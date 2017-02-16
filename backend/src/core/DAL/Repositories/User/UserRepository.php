<?php

namespace Core\DAL\Repositories\User;

use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\Validator as ValidatorFactory;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Lib\Repositories\Exceptions\RepositoryNotFoundException;
use Lib\Repositories\Repository;

/**
 * Class UserRepository.
 *
 * @property Hasher hasher
 * @package Core\DAL\Repositories
 */
class UserRepository extends Repository
{
    /**
     * UserRepository constructor.
     *
     * @param ConnectionInterface $dbConnection
     * @param Hasher $hasher
     */
    public function __construct(ConnectionInterface $dbConnection, Hasher $hasher)
    {
        parent::__construct($dbConnection);

        $this->hasher = $hasher;
    }

    /**
     * @inheritdoc
     */
    public function getModelClass() : string
    {
        return \Core\DAL\Models\User::class;
    }

    /**
     * Get user by credentials.
     *
     * @param string $email
     * @param string $password
     * @return mixed
     */
    public function getByCredentials(string $email, string $password)
    {
        $this->applyCriterias();

        $model = $this->query->where('email', $email)->first();

        $this->reset();

        if (is_null($model)) {
            throw new RepositoryNotFoundException(sprintf('%s not found.', class_basename($this->getModelClass())));
        }

        if (!$this->hasher->check($password, $model->password)) {
            throw new RepositoryNotFoundException('Invalid user password.');
        }

        return $model;
    }

    /**
     * @inheritdoc
     */
    public function save($model, array $attributes = [], array $relationNames = [])
    {
        $this->assertModel($model);

        $validator = ValidatorFactory::make(['email' => $model->email], [
            'email' => $model->exists
                ? Rule::unique('users')->ignore($model->getOriginal('email'), 'email')
                : Rule::unique('users'),
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        if ($model->getOriginal('password') !== $model->password) {
            $model->password = $this->hasher->make($model->password);
        }

        parent::save($model, $attributes, $relationNames);
    }
}
