<?php

namespace Core\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Tag.
 *
 * @property int id
 * @property string text
 * @property Collection photos
 * @package Core\Models
 */
class Tag extends Model
{
    /**
     * @inheritdoc
     */
    protected $fillable = [
        'text',
    ];

    /**
     * @inheritdoc
     */
    public $timestamps = false;

    /**
     * Setter for the 'text' attribute.
     *
     * @param string $text
     * @return $this
     */
    public function setTextAttribute($text)
    {
        $this->attributes['text'] = strtolower($text);

        return $this;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function photos()
    {
        return $this->belongsToMany(Photo::class, 'photo_tags');
    }
}
