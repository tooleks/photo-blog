<?php

namespace App\Models;

use function App\Util\fraction_normalize;
use App\Models\Tables\Constant;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Exif.
 *
 * @property int id
 * @property int photo_id
 * @property string data
 * @package App\Models
 */
class Exif extends Model
{
    /**
     * @inheritdoc
     */
    protected $table = Constant::TABLE_EXIF;

    /**
     * @inheritdoc
     */
    protected $attributes = [
        'data' => '',
    ];

    /**
     * @inheritdoc
     */
    protected $casts = [
        'data' => 'array',
    ];

    /**
     * @inheritdoc
     */
    protected $fillable = [
        'data',
    ];

    /**
     * @inheritdoc
     */
    public $timestamps = false;

    /**
     * @return string
     */
    public function toString(): string
    {
        $parts = collect();

        if ($manufacturer = data_get($this->data, 'Make')) {
            $parts->push($manufacturer);
        }

        if ($model = data_get($this->data, 'Model')) {
            $parts->push($model);
        }

        if ($exposure = data_get($this->data, 'ExposureTime')) {
            $parts->push(fraction_normalize((string) $exposure));
        }

        if ($aperture = data_get($this->data, 'COMPUTED.ApertureFNumber')) {
            $parts->push($aperture);
        }

        if ($iso = data_get($this->data, 'ISOSpeedRatings')) {
            $parts->push($iso);
        }

        if ($takenAt = data_get($this->data, 'DateTimeOriginal')) {
            $parts->push(new Carbon($takenAt));
        }

        return $parts->implode(', ');
    }
}
