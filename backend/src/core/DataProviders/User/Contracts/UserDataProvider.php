<?php

namespace Core\DataProviders\User\Contracts;

use Lib\DataProvider\Contracts\DataProvider;

/**
 * Interface UserDataProvider.
 *
 * @package Core\DataProviders\User\Contracts
 */
interface UserDataProvider extends DataProvider
{
    /**
     * Get user by credentials.
     *
     * @param string $email
     * @param string $password
     * @param array $options
     * @return mixed
     */
    public function getByCredentials(string $email, string $password, array $options = []);
}
