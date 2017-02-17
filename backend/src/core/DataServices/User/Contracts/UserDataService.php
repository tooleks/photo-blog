<?php

namespace Core\DataServices\User\Contracts;

use Lib\DataService\Contracts\DataService;

/**
 * Interface UserDataService.
 *
 * @package Core\DataServices\User\Contracts
 */
interface UserDataService extends DataService
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
