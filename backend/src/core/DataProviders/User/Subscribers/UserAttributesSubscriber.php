<?php

namespace Core\DataProviders\User\Subscribers;

use Core\DataProviders\User\UserDataProvider;
use Core\Models\User;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\Validator as ValidatorFactory;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/**
 * Class UserAttributesSubscriber.
 *
 * @package Core\DataProviders\User\Subscribers
 */
class UserAttributesSubscriber
{
    /**
     * Register the listeners for the subscriber.
     *
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            UserDataProvider::class . '@beforeSave',
            static::class . '@onBeforeSave'
        );
    }

    /**
     * Handle before save event.
     *
     * @param User $user
     * @param array $attributes
     * @param array $options
     * @throws ValidationException
     */
    public function onBeforeSave(User $user, array $attributes = [], array $options = [])
    {
        $validator = ValidatorFactory::make(['email' => $attributes['email'] ?? $user->email], [
            'email' => $user->exists
                ? Rule::unique('users')->ignore($user->getOriginal('email'), 'email')
                : Rule::unique('users'),
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
