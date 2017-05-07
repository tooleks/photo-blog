<?php

namespace Core\Models;

use Core\Models\Builders\ThumbnailBuilder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Thumbnail.
 *
 * @property int id
 * @property string path
 * @property string relative_url
 * @property float width
 * @property float height
 * @package Core\Models
 */
class Thumbnail extends Model
{
    /**
     * @inheritdoc
     */
    protected $fillable = [
        'path',
        'relative_url',
        'width',
        'height',
    ];

    /**
     * @inheritdoc
     */
    public $timestamps = false;

    /**
     * @inheritdoc
     */
    public function newEloquentBuilder($query)
    {
        return new ThumbnailBuilder($query);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function photos()
    {
        return $this->belongsToMany(Photo::class, 'photo_thumbnails');
    }
}
