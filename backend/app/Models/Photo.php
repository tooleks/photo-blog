<?php

namespace App\Models;

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
 * @property bool is_uploaded
 * @property string short_description
 * @property Collection $tags
 * @property Collection $thumbnails
 * @method static Builder isDraft(bool $isDraft)
 * @package App\Models
 */
class Photo extends Model
{
    /**
     * @inheritdoc
     */
    protected $casts = [
        'is_draft' => 'boolean',
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
        'description',
        'path',
        'relative_url',
        'is_draft',
    ];

    /**
     * @inheritdoc
     */
    protected $hidden = [
        'path',
        'relative_url',
        'is_draft',
    ];

    /**
     * @inheritdoc
     */
    protected $appends = [
        'is_uploaded',
        'is_published',
        'absolute_url',
    ];

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
     * Getter for the 'is_uploaded' virtual attribute.
     *
     * @return bool
     */
    public function getIsUploadedAttribute()
    {
        return (bool)$this->path;
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
     * Getter for the 'absolute_url' virtual attribute.
     *
     * @return string|null
     */
    public function getAbsoluteUrlAttribute()
    {
        return $this->relative_url ? url(config('main.website.url')) . $this->relative_url : '';
    }

    /**
     * Scope a query to only include photos of a given 'is_draft' attribute value.
     *
     * @param Builder $query
     * @param bool $isDraft
     * @return Builder
     */
    public function scopeIsDraft($query, $isDraft)
    {
        return $query->where('is_draft', $isDraft);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'photo_tags');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function thumbnails()
    {
        return $this->hasMany(PhotoThumbnail::class, 'photo_id');
    }
}
