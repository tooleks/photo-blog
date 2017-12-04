<?php

namespace App\Managers\Post;

use App\Managers\Post\Contracts\PostManager as PostManagerContract;
use App\Models\Builders\PostBuilder;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Contracts\Auth\Guard as Auth;
use Illuminate\Database\ConnectionInterface as Database;

/**
 * Class PostManager.
 *
 * @package App\Managers\Post
 */
class PostManager implements PostManagerContract
{
    /**
     * @var Database
     */
    private $database;

    /**
     * @var Auth
     */
    private $auth;

    /**
     * @var PostValidator
     */
    private $validator;

    /**
     * PostManager constructor.
     *
     * @param Database $database
     * @param Auth $auth
     * @param PostValidator $validator
     */
    public function __construct(Database $database, Auth $auth, PostValidator $validator)
    {
        $this->database = $database;
        $this->auth = $auth;
        $this->validator = $validator;
    }

    /**
     * Synchronize tags relation records.
     *
     * @param Post $post
     * @param array $rawTags
     * @return void
     */
    private function syncTags(Post $post, array $rawTags): void
    {
        $post->tags()->sync(
            collect($rawTags)
                ->map(function (array $attributes) {
                    return (new Tag)->newQuery()->firstOrCreate(['value' => $attributes['value']]);
                })
                ->pluck('id')
                ->toArray()
        );

        $post->load('tags');
    }

    /**
     * Synchronize photos relation records.
     *
     * @param Post $post
     * @param int $photoId
     * @return void
     */
    private function syncPhoto(Post $post, int $photoId): void
    {
        $post->photos()->sync(array_wrap($photoId));

        $post->load('photos');
    }

    /**
     * @inheritdoc
     */
    public function create(array $attributes): Post
    {
        $attributes = $this->validator->validateForCreate($attributes);

        $post = (new Post)->fill($attributes);

        $this->database->transaction(function () use ($post, $attributes) {
            $post->save();
            if (isset($attributes['tags'])) {
                $this->syncTags($post, $attributes['tags']);
            }
            if (isset($attributes['photo_id'])) {
                $this->syncPhoto($post, $attributes['photo_id']);
            }
        });

        return $post;
    }

    /**
     * @inheritdoc
     */
    public function update(Post $post, array $attributes = []): void
    {
        $attributes = $this->validator->validateForUpdate($post, $attributes);

        $post->fill($attributes);

        $this->database->transaction(function () use ($post, $attributes) {
            $post->save();
            if (isset($attributes['tags'])) {
                $this->syncTags($post, $attributes['tags']);
            }
            if (isset($attributes['photo_id'])) {
                $this->syncPhoto($post, $attributes['photo_id']);
            }
        });
    }

    /**
     * @inheritdoc
     */
    public function getById(int $id): Post
    {
        $post = (new Post)
            ->newQuery()
            ->withPhoto()
            ->withTags()
            ->when(!$this->auth->user() || !$this->auth->user()->can('view-unpublished-posts'), function (PostBuilder $query) {
                return $query->whereIsPublished();
            })
            ->findOrFail($id);

        return $post;
    }

    /**
     * @inheritdoc
     */
    public function paginate(int $page, int $perPage, array $filters = [])
    {
        $filters = $this->validator->validateForPaginate($filters);

        $query = (new Post)
            ->newQuery()
            ->withPhoto()
            ->withTags()
            ->orderByCreatedAtDesc()
            ->when(isset($filters['tag']), function (PostBuilder $query) use ($filters) {
                return $query->whereTagValueEquals($filters['tag']);
            })
            ->when(isset($filters['search_phrase']), function (PostBuilder $query) use ($filters) {
                return $query->searchByPhrase($filters['search_phrase']);
            })
            ->when(!optional($this->auth->user())->can('view-unpublished-posts'), function (PostBuilder $query) {
                return $query->whereIsPublished();
            });

        $paginator = $query->paginate($perPage, ['*'], 'page', $page)->appends($filters);

        return $paginator;
    }

    /**
     * @inheritdoc
     */
    public function delete(Post $post): void
    {
        $this->database->transaction(function () use ($post) {
            $post->delete();
        });
    }
}
