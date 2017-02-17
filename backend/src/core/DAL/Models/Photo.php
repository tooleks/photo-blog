<?php

namespace Core\DAL\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Photo.
 *
 * @property int id
 * @property int user_id
 * @property string description
 * @property string path
 * @property string relative_url
 * @property bool is_published
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property string directory_path
 * @property Exif $exif
 * @property Collection $tags
 * @property Collection $thumbnails
 * @package Core\DAL\Models
 */
class Photo extends Model
{
    /**
     * @inheritdoc
     */
    protected $casts = [
        'is_published' => 'boolean',
    ];

    /**
     * @inheritdoc
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @inheritdoc
     */
    protected $fillable = [
        'user_id',
        'path',
        'relative_url',
        'description',
    ];

    /**
     * @inheritdoc
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function (Photo $photo) {
            $photo->thumbnails()->delete();
            $photo->thumbnails()->detach();
            $photo->tags()->delete();
            $photo->tags()->detach();
        });
    }

    /**
     * Setter for the 'is_published' attribute.
     *
     * @param bool $isPublished
     * @return $this
     */
    public function setIsPublishedAttribute($isPublished)
    {
        $this->attributes['is_published'] = (bool)$isPublished;

        return $this;
    }

    /**
     * Setter for the 'description' attribute.
     *
     * @param string $description
     * @return $this
     */
    public function setDescriptionAttribute($description)
    {
        $this->attributes['description'] = trim($description);

        return $this;
    }

    /**
     * Getter for the 'directory_path' virtual attribute.
     *
     * @return string|null
     */
    public function getDirectoryPathAttribute()
    {
        return $this->path ? pathinfo($this->path, PATHINFO_DIRNAME) : null;
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
        return $this->belongsToMany(Tag::class, 'photo_tags');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function thumbnails()
    {
        return $this->belongsToMany(Thumbnail::class, 'photo_thumbnails')->orderBy('width')->orderBy('height');
    }
}
