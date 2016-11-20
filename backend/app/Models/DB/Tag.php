<?php

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Tag.
 * @property int id
 * @property string text
 * @property Collection photos
 * @package App\Models\DB
 */
class Tag extends Model
{
    /**
     * @inheritdoc
     */
    protected $fillable = ['text'];

    /**
     * @inheritdoc
     */
    public $timestamps = false;

    /**
     * Setter for the 'text' attribute.
     *
     * @param string $value
     */
    public function setTextAttribute($value)
    {
        $this->attributes['text'] = strtolower($value);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function photos()
    {
        return $this->belongsToMany(Photo::class, 'photo_tags');
    }
}
