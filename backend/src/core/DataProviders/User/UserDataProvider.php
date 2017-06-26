<?php

namespace Core\DataProviders\User;

use Core\DataProviders\User\Contracts\UserDataProvider as UserDataProviderContract;
use Lib\DataProvider\DataProvider;

/**
 * Class UserDataProvider.
 *
 * @package Core\DataProviders
 */
class UserDataProvider extends DataProvider implements UserDataProviderContract
{
    /**
     * @inheritdoc
     */
    public function getModelClass(): string
    {
        return \Core\Models\User::class;
    }
}
