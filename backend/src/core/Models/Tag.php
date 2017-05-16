<?php

namespace Core\Models;

use Core\Models\Builders\TagBuilder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Tag.
 *
 * @property int id
 * @property string value
 * @property Collection photos
 * @package Core\Models
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
    public function newEloquentBuilder($query)
    {
        return new TagBuilder($query);
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

    /**
     * Getter for the 'value' attribute.
     *
     * @return string
     */
    public function getValueAttribute()
    {
        return $this->attributes['value'];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function photos()
    {
        return $this->belongsToMany(Photo::class, 'photo_tags');
    }
}
