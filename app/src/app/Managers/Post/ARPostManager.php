<?php

namespace App\Managers\Post;

use App\Models\Builders\PostBuilder;
use App\Models\Photo;
use App\Models\Post;
use App\Models\Tag;
use Core\Contracts\PostManager;
use Core\Entities\PostEntity;
use Illuminate\Contracts\Auth\Guard as Auth;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\ConnectionInterface as Database;

/**
 * Class ARPostManager.
 *
 * @package App\Managers\Post
 */
class ARPostManager implements PostManager
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
     * ARPostManager constructor.
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
    }

    /**
     * Synchronize photos relation records.
     *
     * @param Post $post
     * @param array $rawPhotos
     * @return void
     */
    private function syncPhotos(Post $post, array $rawPhotos): void
    {
        $post->photos()->sync(
            collect($rawPhotos)
                ->filter(function (array $attributes) {
                    return (new Photo)->newQuery()->find($attributes['id']);
                })
                ->pluck('id')
                ->toArray()
        );
    }

    /**
     * @inheritdoc
     */
    public function create(array $attributes): PostEntity
    {
        $defaultAttributes = ['created_by_user_id' => $this->auth->id()];

        $attributes = array_merge($attributes, $defaultAttributes);

        $attributes = $this->validator->validateForCreate($attributes);

        $post = (new Post)->fill($attributes);

        $this->database->transaction(function () use ($post, $attributes) {
            $post->save();
            if (isset($attributes['tags'])) {
                $this->syncTags($post, $attributes['tags']);
            }
            if (isset($attributes['photo'])) {
                $this->syncPhotos($post, [$attributes['photo']]);
            }
        });

        return $post->loadEntityRelations()->toEntity();
    }

    /**
     * @inheritdoc
     */
    public function updateById(int $id, array $attributes): PostEntity
    {
        /** @var Post $post */
        $post = (new Post)
            ->newQuery()
            ->withEntityRelations()
            ->findOrFail($id);

        $attributes = $this->validator->validateForUpdate($post, $attributes);

        $post->fill($attributes);

        $this->database->transaction(function () use ($post, $attributes) {
            $post->save();
            if (isset($attributes['tags'])) {
                $this->syncTags($post, $attributes['tags']);
            }
            if (isset($attributes['photo'])) {
                $this->syncPhotos($post, [$attributes['photo']]);
            }
        });

        return $post->loadEntityRelations()->toEntity();
    }

    /**
     * @inheritdoc
     */
    public function getById(int $id, array $filters = []): PostEntity
    {
        $filters = $this->validator->validateForFiltering($filters);

        /** @var Post $post */
        $post = (new Post)
            ->newQuery()
            ->withEntityRelations()
            ->when(isset($filters['tag']), function (PostBuilder $query) use ($filters) {
                return $query->whereTagValueEquals($filters['tag']);
            })
            ->when(isset($filters['search_phrase']), function (PostBuilder $query) use ($filters) {
                return $query->searchByPhrase($filters['search_phrase']);
            })
            ->when(!$this->auth->user() || !$this->auth->user()->can('view-unpublished-posts'), function (PostBuilder $query) {
                return $query->whereIsPublished();
            })
            ->findOrFail($id);

        return $post->toEntity();
    }

    /**
     * @inheritdoc
     */
    public function getBeforeId(int $id, array $filters = []): PostEntity
    {
        $filters = $this->validator->validateForFiltering($filters);

        /** @var Post $post */
        $post = (new Post)
            ->newQuery()
            ->withEntityRelations()
            ->whereIdLessThan($id)
            ->orderByIdDesc()
            ->when(isset($filters['tag']), function (PostBuilder $query) use ($filters) {
                return $query->whereTagValueEquals($filters['tag']);
            })
            ->when(isset($filters['search_phrase']), function (PostBuilder $query) use ($filters) {
                return $query->searchByPhrase($filters['search_phrase']);
            })
            ->when(!$this->auth->user() || !$this->auth->user()->can('view-unpublished-posts'), function (PostBuilder $query) {
                return $query->whereIsPublished();
            })
            ->firstOrFail();

        return $post->toEntity();
    }

    /**
     * @inheritdoc
     */
    public function getAfterId(int $id, array $filters = []): PostEntity
    {
        $filters = $this->validator->validateForFiltering($filters);

        /** @var Post $post */
        $post = (new Post)
            ->newQuery()
            ->withEntityRelations()
            ->whereIdGreaterThan($id)
            ->orderByIdAsc()
            ->when(isset($filters['tag']), function (PostBuilder $query) use ($filters) {
                return $query->whereTagValueEquals($filters['tag']);
            })
            ->when(isset($filters['search_phrase']), function (PostBuilder $query) use ($filters) {
                return $query->searchByPhrase($filters['search_phrase']);
            })
            ->when(!$this->auth->user() || !$this->auth->user()->can('view-unpublished-posts'), function (PostBuilder $query) {
                return $query->whereIsPublished();
            })
            ->firstOrFail();

        return $post->toEntity();
    }

    /**
     * @inheritdoc
     */
    public function paginate(int $page, int $perPage, array $filters = []): LengthAwarePaginator
    {
        $filters = $this->validator->validateForFiltering($filters);

        $query = (new Post)
            ->newQuery()
            ->withEntityRelations()
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

        $paginator->getCollection()->transform(function (Post $subscription) {
            return $subscription->toEntity();
        });

        return $paginator;
    }

    /**
     * @inheritdoc
     */
    public function deleteById(int $id): PostEntity
    {
        /** @var Post $post */
        $post = (new Post)
            ->newQuery()
            ->withEntityRelations()
            ->findOrFail($id);

        $this->database->transaction(function () use ($post) {
            $post->delete();
        });

        return $post->toEntity();
    }
}
