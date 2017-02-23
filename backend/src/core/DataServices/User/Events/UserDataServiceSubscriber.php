<?php

namespace Core\DataServices\User\Events;

use Core\DataServices\User\UserDataService;
use Core\Models\User;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Support\Facades\Validator as ValidatorFactory;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/**
 * Class UserDataServiceSubscriber.
 *
 * @property Hasher hasher
 * @package Core\DataServices\User\Events
 */
class UserDataServiceSubscriber
{
    /**
     * UserDataServiceSubscriber constructor.
     *
     * @param Hasher $hasher
     */
    public function __construct(Hasher $hasher)
    {
        $this->hasher = $hasher;
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            UserDataService::class . '@beforeSave',
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
        $validator = ValidatorFactory::make(['email' => $user->email], [
            'email' => $user->exists
                ? Rule::unique('users')->ignore($user->getOriginal('email'), 'email')
                : Rule::unique('users'),
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        if ($user->getOriginal('password') !== $user->password) {
            $user->password = $this->hasher->make($user->password);
        }
    }
}
