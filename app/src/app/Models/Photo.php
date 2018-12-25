<?php

namespace App\Models;

use App\Models\Builders\PhotoBuilder;
use App\Models\Tables\Constant;
use Carbon\Carbon;
use Core\Entities\PhotoEntity;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Photo.
 *
 * @property int id
 * @property int created_by_user_id
 * @property int location_id
 * @property string path
 * @property string avg_color
 * @property array metadata
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property User createdByUser
 * @property User location
 * @property Collection thumbnails
 * @property Post post
 * @property Collection posts
 * @package App\Models
 */
class Photo extends Model
{
    /**
     * @inheritdoc
     */
    protected $attributes = [
        'path' => '',
        'avg_color' => '',
        'metadata' => '',
    ];

    /**
     * @inheritdoc
     */
    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * @inheritdoc
     */
    protected $fillable = [
        'created_by_user_id',
        'path',
    ];

    /**
     * @var array
     */
    public static $entityRelations = [
        'location',
        'thumbnails',
    ];

    /**
     * @inheritdoc
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function (self $photo) {
            $photo->thumbnails()->detach();
        });
    }

    /**
     * @inheritdoc
     */
    public function newEloquentBuilder($query): PhotoBuilder
    {
        return new PhotoBuilder($query);
    }

    /**
     * @inheritdoc
     */
    public function newQuery(): PhotoBuilder
    {
        return parent::newQuery();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function thumbnails()
    {
        return $this->belongsToMany(Thumbnail::class, Constant::TABLE_PHOTOS_THUMBNAILS)
            ->orderBy('width')
            ->orderBy('height');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class, Constant::TABLE_POSTS_PHOTOS);
    }

    /**
     * @return Post|null
     */
    public function getPostAttribute(): ?Post
    {
        $this->setRelation('post', collect($this->posts)->first());

        $post = $this->getRelation('post');

        return $post;
    }

    /**
     * @return $this
     */
    public function loadEntityRelations(): Photo
    {
        return $this->load(static::$entityRelations);
    }

    /**
     * @return PhotoEntity
     */
    public function toEntity(): PhotoEntity
    {
        return new PhotoEntity([
            'id' => $this->id,
            'created_by_user_id' => $this->created_by_user_id,
            'path' => $this->path,
            'avg_color' => $this->avg_color,
            'metadata' => $this->metadata,
            'created_at' => $this->created_at->toAtomString(),
            'updated_at' => $this->updated_at->toAtomString(),
            'location' => $this->location ? $this->location->toArray() : null,
            'thumbnails' => $this->thumbnails->toArray(),
        ]);
    }
}
