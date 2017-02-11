<?php

namespace App\Models\DB;

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
 * @method static Photo whereId(int $id)
 * @method static Photo orderByCreatedAt($direction)
 * @method static Photo withExif()
 * @method static Photo withTags()
 * @method static Photo withThumbnails()
 * @package App\Models\DB
 */
class Photo extends Model
{
    /**
     * @inheritdoc
     */
    protected $casts = ['is_published' => 'boolean'];

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
     */
    public function setIsPublishedAttribute($isPublished)
    {
        $this->attributes['is_published'] = (bool)$isPublished;
    }

    /**
     * Setter for the 'description' attribute.
     *
     * @param string $value
     */
    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = trim($value);
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
     * @param Builder $query
     * @param string $searchQuery
     * @return Builder
     */
    public function scopeWhereSearchQuery($query, $searchQuery)
    {
        return $query
            ->select('photos.*')
            ->distinct()
            ->join('photo_tags', 'photo_tags.photo_id', '=', 'photos.id')
            ->join('tags', 'tags.id', '=', 'photo_tags.tag_id')
            ->where(function ($query) use ($searchQuery) {
                $query
                    ->where('photos.description', 'like', "%$searchQuery%")
                    ->orWhere('tags.text', 'like', "%$searchQuery%");
            });
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
            ->distinct()
            ->join('photo_tags', 'photo_tags.photo_id', '=', 'photos.id')
            ->join('tags', 'tags.id', '=', 'photo_tags.tag_id')
            ->where('tags.text', 'like', "%$tag%");
    }

    /**
     * Scope a query to order photos by 'created_at' attribute.
     *
     * @param Builder $query
     * @param string $direction
     * @return Builder
     */
    public function scopeOrderByCreatedAt($query, $direction)
    {
        return $query->orderBy('created_at', $direction);
    }

    /**
     * Scope a query to include exif relation.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeWithExif($query)
    {
        return $query->with('exif');
    }

    /**
     * Scope a query to include tags relation.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeWithTags($query)
    {
        return $query->with('tags');
    }

    /**
     * Scope a query to include thumbnails relation.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeWithThumbnails($query)
    {
        return $query->with('thumbnails');
    }
}
