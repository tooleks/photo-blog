<?php

namespace App\Models;

use App\Models\Builders\ThumbnailBuilder;
use Core\Entities\ThumbnailEntity;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Thumbnail.
 *
 * @property int id
 * @property string path
 * @property int width
 * @property int height
 * @package App\Models
 */
class Thumbnail extends Model
{
    /**
     * @inheritdoc
     */
    public $timestamps = false;

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
    public function newEloquentBuilder($query): ThumbnailBuilder
    {
        return new ThumbnailBuilder($query);
    }

    /**
     * @inheritdoc
     */
    public function newQuery(): ThumbnailBuilder
    {
        return parent::newQuery();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function photos()
    {
        return $this->belongsToMany(Photo::class, 'photos_thumbnails');
    }

    /**
     * @return ThumbnailEntity
     */
    public function toEntity(): ThumbnailEntity
    {
        return new ThumbnailEntity([
            'path' => $this->path,
            'width' => $this->width,
            'height' => $this->height,
        ]);
    }
}
