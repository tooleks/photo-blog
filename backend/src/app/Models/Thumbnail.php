<?php

namespace App\Models;

use App\Models\Builders\ThumbnailBuilder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Thumbnail.
 *
 * @property int id
 * @property string path
 * @property float width
 * @property float height
 * @package App\Models
 */
class Thumbnail extends Model
{
    /**
     * @inheritdoc
     */
    protected $fillable = [
        'path',
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
