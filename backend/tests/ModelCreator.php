<?php

namespace Tests;

use App\Models\Exif;
use App\Models\Photo;
use App\Models\Subscription;
use App\Models\Tag;
use App\Models\Thumbnail;
use App\Models\User;
use App\Models\Role;

/**
 * Trait ModelCreator.
 */
trait ModelCreator
{
    protected function createAdministratorUser(array $attributes = []): User
    {
        $defaultAttributes = ['role_id' => (new Role)->newQuery()->whereNameAdministrator()->firstOrFail()->id];

        if (isset($attributes['password'])) {
            $attributes['password'] = bcrypt($attributes['password']);
        }

        /** @var User $user */

        $user = factory(User::class)->make()->forceFill(array_merge($defaultAttributes, $attributes));

        $user->save();

        return $user;
    }
    protected function createCustomerUser(array $attributes = []): User
    {
        $defaultAttributes = ['role_id' => (new Role)->newQuery()->whereNameCustomer()->firstOrFail()->id];

        if (isset($attributes['password'])) {
            $attributes['password'] = bcrypt($attributes['password']);
        }

        /** @var User $user */

        $user = factory(User::class)->make()->forceFill(array_merge($defaultAttributes, $attributes));

        $user->save();

        return $user;
    }

    protected function createPhoto(array $attributes = []): Photo
    {
        /** @var Photo $photo */

        $photo = factory(Photo::class)->make()->forceFill($attributes);

        $photo->save();

        $photo->exif()->save(factory(Exif::class)->make());

        $photo->thumbnails()->save(factory(Thumbnail::class)->make());
        $photo->thumbnails()->save(factory(Thumbnail::class)->make());

        return $photo;
    }

    protected function createPublishedPhoto(array $attributes = []): Photo
    {
        /** @var Photo $photo */

        $photo = $this->createPhoto($attributes);

        $photo->tags()->save(factory(Tag::class)->make());

        return $photo;
    }

    protected function createTag(array $attributes = []): Tag
    {
        /** @var Tag $tag */

        $tag = factory(Tag::class)->make()->forceFill($attributes);

        $tag->save();

        return $tag;
    }

    protected function createSubscription(array $attributes = []): Subscription
    {
        /** @var Subscription $subscription */

        $subscription = factory(Subscription::class)->make()->forceFill($attributes);

        $subscription->save();

        return $subscription;
    }
}
