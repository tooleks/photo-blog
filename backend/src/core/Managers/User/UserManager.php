<?php

namespace Core\Managers\User;

use Core\DataProviders\User\Contracts\UserDataProvider;
use Core\Managers\User\Contracts\UserManager as UserManagerContracts;
use Core\Models\User;
use Illuminate\Contracts\Hashing\Hasher;
use Lib\DataProvider\Criterias\WhereEmail;
use Lib\DataProvider\Exceptions\DataProviderNotFoundException;

/**
 * Class UserManager.
 *
 * @package Core\Managers\User
 */
class UserManager implements UserManagerContracts
{
    /**
     * @var UserDataProvider
     */
    private $userDataProvider;

    /**
     * @var Hasher
     */
    private $hasher;

    /**
     * UserManager constructor.
     *
     * @param UserDataProvider $userDataProvider
     * @param Hasher $hasher
     */
    public function __construct(UserDataProvider $userDataProvider, Hasher $hasher)
    {
        $this->userDataProvider = $userDataProvider;
        $this->hasher = $hasher;
    }

    /**
     * @inheritdoc
     */
    public function getById(int $id): User
    {
        return $this->userDataProvider->getById($id);
    }

    /**
     * @inheritdoc
     */
    public function getByEmail(string $email): User
    {
        return $this->userDataProvider
            ->applyCriteria(new WhereEmail($email))
            ->getFirst();
    }

    /**
     * @inheritdoc
     */
    public function getByCredentials(string $email, string $password): User
    {
        $user = $this->getByEmail($email);

        if (!$this->hasher->check($password, $user->password)) {
            throw new DataProviderNotFoundException('Invalid user password.');
        }

        return $user;
    }

    /**
     * @inheritdoc
     */
    public function reGenerateApiToken(User $user)
    {
        $user->api_token = str_random(64);

        $this->userDataProvider->save($user);
    }

    /**
     * @inheritdoc
     */
    public function createCustomer(array $attributes = []): User
    {
        $user = new User;

        $user->password = $this->hasher->make($attributes['password']);
        $user->api_token = str_random(64);
        $user->setCustomerRoleId();

        $this->userDataProvider->save($user, $attributes);

        return $user;
    }

    /**
     * @inheritdoc
     */
    public function createAdministrator(array $attributes = []): User
    {
        $user = new User;

        $user->password = $this->hasher->make($attributes['password']);
        $user->api_token = str_random(64);
        $user->setAdministratorRoleId();

        $this->userDataProvider->save($user, $attributes);

        return $user;
    }

    /**
     * @inheritdoc
     */
    public function save(User $user, array $attributes = [])
    {
        if (isset($attributes['password'])) {
            $user->password = $this->hasher->make($attributes['password']);
        }

        $this->userDataProvider->save($user, $attributes);

        return $user;
    }

    /**
     * @inheritdoc
     */
    public function delete(User $user)
    {
        $this->userDataProvider->delete($user);
    }
}
