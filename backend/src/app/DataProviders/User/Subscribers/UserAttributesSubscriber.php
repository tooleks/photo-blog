<?php

namespace App\DataProviders\User\Subscribers;

use App\DataProviders\User\UserDataProvider;
use App\Managers\Photo\Contracts\PhotoManager;
use App\Models\Photo;
use App\Models\User;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Validator as ValidatorFactory;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/**
 * Class UserAttributesSubscriber.
 *
 * @package App\DataProviders\User\Subscribers
 */
class UserAttributesSubscriber
{
    /**
     * @var Application
     */
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

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

        $events->listen(
            UserDataProvider::class . '@beforeDelete',
            static::class . '@onBeforeDelete'
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


    /**
     * Handle before delete event.
     *
     * @param User $user
     * @param array $options
     * @throws ValidationException
     */
    public function onBeforeDelete(User $user, array $options = [])
    {
        $photoManager = $this->app->make(PhotoManager::class);

        $photoManager->eachCreatedByUserId(function (Photo $photo) use ($photoManager) {
            $photoManager->delete($photo);
        }, $user->id);
    }
}
