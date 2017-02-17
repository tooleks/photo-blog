<?php

namespace Core\DAL\DataServices\User\Contracts;

use Lib\DataService\Contracts\DataService;

/**
 * Interface UserDataService.
 *
 * @package Core\DAL\DataServices\User\Contracts
 */
interface UserDataService extends DataService
{
    /**
     * Get user by credentials.
     *
     * @param string $email
     * @param string $password
     * @return mixed
     */
    public function getByCredentials(string $email, string $password);
}
