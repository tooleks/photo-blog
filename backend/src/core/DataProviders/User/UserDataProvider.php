<?php

namespace Core\DataProviders\User;

use Core\DataProviders\User\Contracts\UserDataProvider as UserDataProviderContract;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\ConnectionInterface as Connection;
use Lib\DataProvider\Exceptions\DataProviderNotFoundException;
use Lib\DataProvider\DataProvider;

/**
 * Class UserDataProvider.
 *
 * @property Hasher hasher
 * @package Core\DataProviders
 */
class UserDataProvider extends DataProvider implements UserDataProviderContract
{
    /**
     * UserDataProvider constructor.
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
        return \Core\Models\User::class;
    }

    /**
     * @inheritdoc
     */
    public function getByCredentials(string $email, string $password, array $options = [])
    {
        $this->dispatchEvent('beforeGetByCredentials', $this->query, $options);

        $model = $this->query->where('email', $email)->first();

        if (is_null($model)) {
            throw new DataProviderNotFoundException(sprintf('%s not found.', class_basename($this->getModelClass())));
        }

        if (!$this->hasher->check($password, $model->password)) {
            throw new DataProviderNotFoundException('Invalid user password.');
        }

        $this->dispatchEvent('afterGetByCredentials', $model, $options);

        $this->reset();

        return $model;
    }
}
