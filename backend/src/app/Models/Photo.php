<?php

namespace App\Models;

use App\Models\Builders\PhotoBuilder;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Photo.
 *
 * @property int id
 * @property int created_by_user_id
 * @property string description
 * @property string path
 * @property string directory_path
 * @property string avg_color
 * @property bool is_published
 * @property Carbon published_at
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property User createdByUser
 * @property Exif exif
 * @property Collection tags
 * @property Collection thumbnails
 * @package App\Models
 */
class Photo extends Model
{
    /**
     * @inheritdoc
     */
    protected $attributes = [
        'description' => '',
        'path' => '',
        'avg_color' => '',
    ];

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
        // Database attributes.
        'created_by_user_id',
        'description',
        'path',
        'avg_color',
        // Virtual attributes.
        'is_published',
    ];

    /**
     * @inheritdoc
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function (Photo $photo) {
            $photo->exif()->delete();
            $photo->tags()->detach();
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
     * @return null|string
     */
    public function getDirectoryPath(): ?string
    {
        if (isset($this->attributes['path'])) {
            return dirname($this->attributes['path']);
        }

        return null;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescriptionAttribute(string $description)
    {
        $this->attributes['description'] = trim($description);

        return $this;
    }

    /**
     * @param bool $isPublished
     * @return $this
     */
    public function setIsPublishedAttribute(bool $isPublished)
    {
        $this->attributes['published_at'] = $isPublished ? Carbon::now() : null;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsPublishedAttribute(): bool
    {
        if (isset($this->attributes['published_at'])) {
            return (bool) $this->attributes['published_at'];
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isPublished(): bool
    {
        return $this->getIsPublishedAttribute();
    }

    /**
     * @return bool
     */
    public function isUnpublished(): bool
    {
        return !$this->getIsPublishedAttribute();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
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
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'photos_tags');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function thumbnails()
    {
        return $this->belongsToMany(Thumbnail::class, 'photos_thumbnails')->orderBy('width')->orderBy('height');
    }
}
