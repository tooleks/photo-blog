<?php

namespace App\Models;

use App\Models\Builders\PostBuilder;
use App\Models\Tables\Constant;
use Carbon\Carbon;
use Core\Entities\PostEntity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Class Post.
 *
 * @property int id
 * @property int created_by_user_id
 * @property string description
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Carbon published_at
 * @property bool is_published
 * @property User createdByUser
 * @property Collection photos
 * @property Photo photo
 * @property Collection tags
 * @package App\Models
 */
class Post extends Model
{
    /**
     * @inheritdoc
     */
    protected $dates = [
        'published_at',
        'created_at',
        'updated_at',
    ];

    /**
     * @inheritdoc
     */
    protected $fillable = [
        'created_by_user_id',
        'description',
        'is_published',
    ];

    /**
     * @inheritdoc
     */
    protected $attributes = [
        'description' => '',
    ];

    /**
     * @var array
     */
    public static $entityRelations = [
        'tags',
        'photos',
        'photos.location',
        'photos.thumbnails',
    ];

    /**
     * @inheritdoc
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function (self $post) {
            $post->tags()->detach();
            $post->photos()->detach();
        });
    }

    /**
     * @inheritdoc
     */
    public function newEloquentBuilder($query): PostBuilder
    {
        return new PostBuilder($query);
    }

    /**
     * @inheritdoc
     */
    public function newQuery(): PostBuilder
    {
        return parent::newQuery();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, Constant::TABLE_POSTS_TAGS);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function photos()
    {
        return $this->belongsToMany(Photo::class, Constant::TABLE_POSTS_PHOTOS);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * @return Photo|null
     */
    public function getPhotoAttribute(): ?Photo
    {
        $this->setRelation('photo', collect($this->photos)->first());

        $photo = $this->getRelation('photo');

        return $photo;
    }

    /**
     * @param bool $isPublished
     * @return $this
     */
    public function setIsPublishedAttribute(bool $isPublished)
    {
        if ($this->is_published !== $isPublished) {
            $this->attributes['published_at'] = $isPublished ? Carbon::now() : null;
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsPublishedAttribute(): bool
    {
        $isPublished = false;

        if (isset($this->attributes['published_at'])) {
            $isPublished = (bool) $this->attributes['published_at'];
        }

        return $isPublished;
    }

    /**
     * @return $this
     */
    public function loadEntityRelations(): Post
    {
        return $this->load(static::$entityRelations);
    }

    /**
     * @return PostEntity
     */
    public function toEntity(): PostEntity
    {
        return new PostEntity([
            'id' => $this->id,
            'created_by_user_id' => $this->created_by_user_id,
            'description' => $this->description,
            'photo' => $this->photo->toArray(),
            'tags' => $this->tags->toArray(),
            'created_at' => $this->created_at->toAtomString(),
            'updated_at' => $this->updated_at->toAtomString(),
            'published_at' => $this->published_at ? $this->published_at->toAtomString() : null,
        ]);
    }
}
