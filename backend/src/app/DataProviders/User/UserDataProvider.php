<?php

namespace App\DataProviders\User;

use App\DataProviders\User\Contracts\UserDataProvider as UserDataProviderContract;
use Lib\DataProvider\Eloquent\EloquentDataProvider;

/**
 * Class UserDataProvider.
 *
 * @package App\DataProviders
 */
class UserDataProvider extends EloquentDataProvider implements UserDataProviderContract
{
    /**
     * @inheritdoc
     */
    public function getModelClass(): string
    {
        return \App\Models\User::class;
    }
}
