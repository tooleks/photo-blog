<?php

namespace App\Managers\User;

use App\DataProviders\User\Contracts\UserDataProvider;
use App\Managers\User\Contracts\UserManager as UserManagerContracts;
use App\Models\User;
use Illuminate\Contracts\Hashing\Hasher;
use Lib\DataProvider\Criterias\WhereEmail;
use Lib\DataProvider\Criterias\WhereName;
use Lib\DataProvider\Exceptions\DataProviderNotFoundException;

/**
 * Class UserManager.
 *
 * @package App\Managers\User
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
    public function getByName(string $name): User
    {
        return $this->userDataProvider
            ->applyCriteria(new WhereName($name))
            ->getFirst();
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
    public function generatePasswordHash(User $user, string $password): void
    {
        $user->password = $this->hasher->make($password);
    }

    /**
     * @inheritdoc
     */
    public function createCustomer(array $attributes = []): User
    {
        $user = new User;

        $user->setCustomerRoleId();

        $this->generatePasswordHash($user, $attributes['password'] ?? '');

        $this->userDataProvider->save($user, $attributes);

        return $user;
    }

    /**
     * @inheritdoc
     */
    public function createAdministrator(array $attributes = []): User
    {
        $user = new User;

        $user->setAdministratorRoleId();

        $this->generatePasswordHash($user, $attributes['password'] ?? '');

        $this->userDataProvider->save($user, $attributes);

        return $user;
    }

    /**
     * @inheritdoc
     */
    public function save(User $user, array $attributes = []): void
    {
        if (isset($attributes['password'])) {
            $this->generatePasswordHash($user, $attributes['password']);
        }

        $this->userDataProvider->save($user, $attributes);
    }

    /**
     * @inheritdoc
     */
    public function delete(User $user): void
    {
        $this->userDataProvider->delete($user);
    }
}
