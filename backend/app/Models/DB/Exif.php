<?php

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Exif.
 *
 * @property int id
 * @property int photo_id
 * @property string data
 * @package App\Models\DB
 */
class Exif extends Model
{
    /**
     * @inheritdoc
     */
    protected $table = 'exif';

    /**
     * @inheritdoc
     */
    protected $casts = ['data' => 'array'];

    /**
     * @inheritdoc
     */
    protected $fillable = ['data'];

    /**
     * @inheritdoc
     */
    public $timestamps = false;
}
