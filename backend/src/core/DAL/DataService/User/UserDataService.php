<?php

namespace Core\DAL\DataService\User;

use Core\DAL\DataService\User\Contracts\UserDataService as UserDataServiceContract;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\ConnectionInterface as Connection;
use Illuminate\Support\Facades\Validator as ValidatorFactory;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Lib\DataService\Exceptions\DataServiceNotFoundException;
use Lib\DataService\DataService;

/**
 * Class UserDataService.
 *
 * @property Hasher hasher
 * @package Core\DAL\DataService
 */
class UserDataService extends DataService implements UserDataServiceContract
{
    /**
     * UserDataService constructor.
     *
     * @param Connection $dbConnection
     * @param Dispatcher $events
     * @param Hasher $hasher
     */
    public function __construct(Connection $dbConnection, Dispatcher $events, Hasher $hasher)
    {
        parent::__construct($dbConnection, $events);

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
     * @inheritdoc
     */
    public function getByCredentials(string $email, string $password)
    {
        $model = $this->query->where('email', $email)->first();

        $this->reset();

        if (is_null($model)) {
            throw new DataServiceNotFoundException(sprintf('%s not found.', class_basename($this->getModelClass())));
        }

        if (!$this->hasher->check($password, $model->password)) {
            throw new DataServiceNotFoundException('Invalid user password.');
        }

        return $model;
    }

    /**
     * @inheritdoc
     */
    public function save($model, array $attributes = [], array $options = [])
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

        parent::save($model, $attributes, $options);
    }
}
