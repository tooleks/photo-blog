<?php

namespace Core\Models;

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
 * @property string relative_url
 * @property string avg_color
 * @property bool is_published
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property string directory_path
 * @property User $createdByUser
 * @property Exif $exif
 * @property Collection $tags
 * @property Collection $thumbnails
 * @package Core\Models
 */
class Photo extends Model
{
    /**
     * @inheritdoc
     */
    protected $attributes = [
        'description' => '',
    ];

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
        'description',
        'path',
        'relative_url',
        'avg_color',
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
            Tag::deleteAllWithNoRelations();
            $photo->thumbnails()->detach();
            Thumbnail::deleteAllWithNoRelations();
        });
    }

    /**
     * Setter for the 'created_by_user_id' attribute.
     *
     * @param int $createdByUserId
     * @return $this
     */
    public function setCreatedByUserIdAttribute(int $createdByUserId)
    {
        $this->attributes['created_by_user_id'] = $createdByUserId;

        return $this;
    }

    /**
     * Setter for the 'is_published' attribute.
     *
     * @param bool $isPublished
     * @return $this
     */
    public function setIsPublishedAttribute(bool $isPublished)
    {
        $this->attributes['is_published'] = $isPublished;

        return $this;
    }

    /**
     * Setter for the 'description' attribute.
     *
     * @param string $description
     * @return $this
     */
    public function setDescriptionAttribute(string $description)
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
