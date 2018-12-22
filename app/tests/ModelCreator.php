<?php

namespace Tests;

use App\Models\Photo;
use App\Models\Post;
use App\Models\Role;
use App\Models\Subscription;
use App\Models\Tag;
use App\Models\Thumbnail;
use App\Models\User;

/**
 * Trait ModelCreator.
 */
trait ModelCreator
{
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

    /**
     * @param array $attributes
     * @return Post
     */
    protected function createPost(array $attributes = []): Post
    {
        $defaultAttributes = [
            'created_by_user_id' => $this->createAdministratorUser()->id,
            'is_published' => true,
        ];

        /** @var Post $post */

        $post = factory(Post::class)->make()->forceFill(array_merge($defaultAttributes, $attributes));

        $post->save();

        $post->photos()->sync([$this->createPhoto()->id]);

        $post->tags()->sync([$this->createTag()->id]);

        $post->load('photos', 'tags');

        return $post;
    }

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

    protected function createPhoto(array $attributes = []): Photo
    {
        $defaultAttributes = [
            'created_by_user_id' => $this->createAdministratorUser()->id,
        ];

        /** @var Photo $photo */

        $photo = factory(Photo::class)->make()->forceFill(array_merge($defaultAttributes, $attributes));

        $photo->save();

        $photo->thumbnails()->save(factory(Thumbnail::class)->make());
        $photo->thumbnails()->save(factory(Thumbnail::class)->make());


        $photo->load('thumbnails');

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
