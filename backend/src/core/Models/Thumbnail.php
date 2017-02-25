<?php

namespace Core\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Thumbnail.
 *
 * @property int id
 * @property string path
 * @property string relative_url
 * @property string width
 * @property string height
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function photos()
    {
        return $this->belongsToMany(Photo::class, 'photo_thumbnails');
    }
}
