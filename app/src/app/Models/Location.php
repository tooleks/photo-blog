<?php

namespace App\Models;

use App\ValueObjects\Coordinates;
use App\Models\Builders\LocationBuilder;
use App\Models\Tables\Constant;
use App\ValueObjects\Latitude;
use App\ValueObjects\Longitude;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Class Location.
 *
 * @property int id
 * @property Coordinates coordinates
 * @package App\Models
 */
class Location extends Model
{
    /**
     * @inheritdoc
     */
    protected $table = Constant::TABLE_LOCATIONS;

    /**
     * @inheritdoc
     */
    protected $fillable = [
        'coordinates',
    ];

    /**
     * @inheritdoc
     */
    public $timestamps = false;

    /**
     * Note: Laravel does not support spatial types.
     * See: https://dev.mysql.com/doc/refman/5.7/en/spatial-type-overview.html
     *
     * @param Coordinates $coordinates
     * @return $this
     */
    public function setCoordinatesAttribute(Coordinates $coordinates)
    {
        $expression = "ST_GeomFromText('POINT({$coordinates})')";

        $this->attributes['coordinates'] = $this->getConnection()->raw($expression);

        return $this;
    }

    /**
     * Note: Laravel does not support spatial types.
     * See: https://dev.mysql.com/doc/refman/5.7/en/spatial-type-overview.html
     *
     * @return Coordinates
     */
    public function getCoordinatesAttribute(): Coordinates
    {
        $text = Str::before(Str::after($this->attributes['coordinates'], 'POINT('), ')');

        [$latitude, $longitude] = explode(' ', $text);

        return new Coordinates(new Latitude($latitude), new Longitude($longitude));
    }

    /**
     * @inheritdoc
     */
    public function newEloquentBuilder($query): LocationBuilder
    {
        return (new LocationBuilder($query))->defaultSelect();
    }

    /**
     * @inheritdoc
     */
    public function newQuery(): LocationBuilder
    {
        return parent::newQuery();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function photos()
    {
        return $this->hasMany(Photo::class, 'location_id');
    }
}
