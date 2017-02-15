<?php

namespace Core\DAL\Models;

use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
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
 * @method static Photo whereIsUploaded()
 * @method static Photo whereIsPublished(bool $isPublished)
 * @method static Photo whereSearchQuery(string $searchQuery)
 * @method static Photo whereTag(string $tag)
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
    protected $with = [
        'exif',
        'thumbnails',
        'tags',
    ];

    /**
     * @inheritdoc
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function (Photo $photo) {
            $photo->exif()->delete();
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
     * @param string $value
     * @return $this
     */
    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = trim($value);

        return $this;
    }

    /**
     * Setter for the 'exif' attribute.
     *
     * @param Exif $exif
     * @return $this
     */
    public function setExifAttribute(Exif $exif)
    {
        $this->exif = $exif;

        return $this;
    }

    /**
     * Setter for the 'thumbnails' attribute.
     *
     * @param array|Collection $thumbnails
     * @return $this
     */
    public function setThumbnailsAttribute($thumbnails)
    {
        $this->thumbnails = new Collection($thumbnails);

        return $this;
    }

    /**
     * Setter for the 'tags' attribute.
     *
     * @param array|Collection $tags
     * @return $this
     */
    public function setTagsAttribute($tags)
    {
        $this->tags = new Collection($tags);

        return $this;
    }

    /**
     * Getter for the 'directory_path' virtual attribute.
     *
     * @return bool
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

    /**
     * Scope a query to include only photos that have 'path' and 'relative_url' attributes.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeWhereIsUploaded($query)
    {
        return $query->orWhere(function ($query) {
            $query
                ->whereNotNull('photos.path')
                ->whereNotNull('photos.relative_url');
        });
    }

    /**
     * Scope a query to include only published photos.
     *
     * @param Builder $query
     * @param bool $isPublished
     * @return Builder
     */
    public function scopeWhereIsPublished($query, $isPublished)
    {
        return $query->where('photos.is_published', $isPublished);
    }

    /**
     * Scope a query to include only photos filtered by search query string.
     *
     * @param Builder $queryBuilder
     * @param string $searchQuery
     * @return Builder
     */
    public function scopeWhereSearchQuery($queryBuilder, $searchQuery)
    {
        return $queryBuilder
            ->select('photos.*')
            ->join('photo_tags AS swsq_photo_tags', 'swsq_photo_tags.photo_id', '=', 'photos.id')
            ->join('tags AS swsq_tags', 'swsq_tags.id', '=', 'swsq_photo_tags.tag_id')
            ->where(function ($queryBuilder) use ($searchQuery) {
                $queryBuilder
                    ->where('photos.description', 'like', "%$searchQuery%")
                    ->orWhere('swsq_tags.text', 'like', "%$searchQuery%");
            })
            ->groupBy('photos.id');
    }

    /**
     * Scope a query to include only photos filtered by tag.
     *
     * @param Builder $query
     * @param string $tag
     * @return Builder
     */
    public function scopeWhereTag($query, $tag)
    {
        return $query
            ->select('photos.*')
            ->join('photo_tags AS swt_photo_tags', 'swt_photo_tags.photo_id', '=', 'photos.id')
            ->join('tags AS swt_tags', 'swt_tags.id', '=', 'swt_photo_tags.tag_id')
            ->where('swt_tags.text', 'like', "%$tag%")
            ->groupBy('photos.id');
    }
}
