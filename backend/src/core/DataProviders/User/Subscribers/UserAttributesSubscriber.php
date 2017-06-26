<?php

namespace Core\DataProviders\User\Subscribers;

use Core\DataProviders\User\UserDataProvider;
use Core\Managers\Photo\Contracts\PhotoManager;
use Core\Models\Photo;
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
     * @var PhotoManager
     */
    private $photoManager;

    /**
     * UserAttributesSubscriber constructor.
     *
     * @param PhotoManager $photoManager
     */
    public function __construct(PhotoManager $photoManager)
    {
        $this->photoManager = $photoManager;
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
        $this->photoManager->eachCreatedByUserId(function (Photo $photo) {
            $this->photoManager->delete($photo);
        }, $user->id);
    }
}
