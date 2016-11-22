<?php

namespace App\Models\DB;

use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Photo.
 * @property int id
 * @property int user_id
 * @property string description
 * @property string path
 * @property string relative_url
 * @property bool is_draft
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property string short_description
 * @property Collection $tags
 * @property Collection $thumbnails
 * @method static Photo whereUploaded()
 * @method static Photo wherePublished($isPublished)
 * @method static Photo whereSearchQuery($searchQuery)
 * @method static Photo whereTag($tag)
 * @method static Photo whereId($id)
 * @method static Photo orderByCreatedAt($direction)
 * @method static Photo withThumbnails()
 * @method static Photo withTags()
 * @package App\Models\DB
 */
class Photo extends Model
{
    /**
     * @inheritdoc
     */
    protected $casts = ['is_draft' => 'boolean'];

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
        'is_draft',
        'path',
        'relative_url',
        'description',
    ];

    /**
     * @inheritdoc
     */
    protected $appends = ['is_published'];

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
     * Getter for the 'is_published' virtual attribute.
     *
     * @return bool
     */
    public function getIsPublishedAttribute()
    {
        return !$this->is_draft;
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
        return $this->belongsToMany(Thumbnail::class, 'photo_thumbnails');
    }

    /**
     * Scope a query to include only photos that have 'path' and 'relative_url' attributes.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeWhereUploaded($query)
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
    public function scopeWherePublished($query, $isPublished = true)
    {
        return $query->where('photos.is_draft', !$isPublished);
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
     * Scope a query to include thumbnails.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeWithThumbnails($query)
    {
        return $query->with('thumbnails');
    }

    /**
     * Scope a query to include tags.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeWithTags($query)
    {
        return $query->with('tags');
    }
}
