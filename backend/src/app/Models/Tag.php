<?php

namespace App\Models;

use App\Models\Builders\TagBuilder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Tag.
 *
 * @property int id
 * @property string value
 * @property Collection photos
 * @package App\Models
 */
class Tag extends Model
{
    /**
     * @inheritdoc
     */
    protected $fillable = [
        'value',
    ];

    /**
     * @inheritdoc
     */
    public $timestamps = false;

    /**
     * @inheritdoc
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function (Tag $tag) {
            $tag->photos()->detach();
        });
    }

    /**
     * @inheritdoc
     */
    public function newEloquentBuilder($query): TagBuilder
    {
        return new TagBuilder($query);
    }

    /**
     * @inheritdoc
     */
    public function newQuery(): TagBuilder
    {
        return parent::newQuery();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function photos()
    {
        return $this->belongsToMany(Photo::class, 'photos_tags');
    }

    /**
     * Setter for the 'value' attribute.
     *
     * @param string $value
     * @return $this
     */
    public function setValueAttribute(string $value)
    {
        $this->attributes['value'] = trim(str_replace(' ', '_', strtolower($value)));

        return $this;
    }
}
