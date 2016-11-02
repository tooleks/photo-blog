<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PhotoThumbnail.
 * @property int id
 * @property int photo_id
 * @property string path
 * @property string relative_url
 * @property string width
 * @property string height
 * @package App\Models
 */
class PhotoThumbnail extends Model
{
    /**
     * @inheritdoc
     */
    protected $fillable = [
        'photo_id',
        'path',
        'relative_url',
        'width',
        'height',
    ];

    /**
     * @inheritdoc
     */
    protected $hidden = [
        'photo_id',
        'path',
        'relative_url',
    ];

    /**
     * @inheritdoc
     */
    public $timestamps = false;

    /**
     * @inheritdoc
     */
    protected $appends = [
        'absolute_url',
    ];

    /**
     * Getter for the 'absolute_url' virtual attribute.
     *
     * @return string|null
     */
    public function getAbsoluteUrlAttribute()
    {
        return $this->relative_url ? url(config('main.website.url')) . $this->relative_url : '';
    }
}
