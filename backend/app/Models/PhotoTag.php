<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PhotoTag.
 * @package App\Models
 */
class PhotoTag extends Model
{
    /**
     * @inheritdoc
     */
    protected $fillable = [
        'photo_id',
        'tag_id',
    ];

    /**
     * @inheritdoc
     */
    public $timestamps = false;
}
