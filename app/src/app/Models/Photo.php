<?php

namespace App\Models;

use App\Models\Builders\PhotoBuilder;
use App\Models\Tables\Constant;
use Carbon\Carbon;
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
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property User createdByUser
 * @property User location
 * @property Exif exif
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
    ];

    /**
     * @inheritdoc
     */
    protected $fillable = [
        'created_by_user_id',
        'location_id',
        'path',
        'avg_color',
    ];

    /**
     * @inheritdoc
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function (self $photo) {
            $photo->exif()->delete();
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
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function exif()
    {
        return $this->hasOne(Exif::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function thumbnails()
    {
        return $this->belongsToMany(Thumbnail::class, Constant::TABLE_PHOTOS_THUMBNAILS)->orderBy('width')->orderBy('height');
    }

    /**
     * @return Post|null
     */
    public function getPostAttribute(): ?Post
    {
        $this->setRelation('post', collect($this->posts)->first());

        $photo = $this->getRelation('post');

        return $photo;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class, Constant::TABLE_POSTS_PHOTOS);
    }
}
