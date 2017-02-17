<?php

namespace Core\DataServices\User;

use Core\DataServices\User\Contracts\UserDataService as UserDataServiceContract;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\ConnectionInterface as Connection;
use Lib\DataService\Exceptions\DataServiceNotFoundException;
use Lib\DataService\DataService;

/**
 * Class UserDataService.
 *
 * @property Hasher hasher
 * @package Core\DataServices
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
        return \Core\Models\User::class;
    }

    /**
     * @inheritdoc
     */
    public function getByCredentials(string $email, string $password, array $options = [])
    {
        $this->dispatchEvent('beforeGetByCredentials', ['query' => $this->query, 'options' => $options]);

        $model = $this->query->where('email', $email)->first();

        $this->reset();

        if (is_null($model)) {
            throw new DataServiceNotFoundException(sprintf('%s not found.', class_basename($this->getModelClass())));
        }

        if (!$this->hasher->check($password, $model->password)) {
            throw new DataServiceNotFoundException('Invalid user password.');
        }

        $this->dispatchEvent('afterGetByCredentials', ['query' => $this->query, 'model' => $model, 'options' => $options]);

        return $model;
    }
}
