<?php

namespace App\Models;

use App\Models\Builders\LocationBuilder;
use App\Models\Tables\Constant;
use Core\Entities\LocationEntity;
use Core\ValueObjects\Coordinates;
use Core\ValueObjects\Latitude;
use Core\ValueObjects\Longitude;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Class Location.
 *
 * Note: Laravel does not support spatial types.
 * See: https://dev.mysql.com/doc/refman/5.7/en/spatial-type-overview.html
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
    public $timestamps = false;

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

    /**
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
     * @return Coordinates
     */
    public function getCoordinatesAttribute(): Coordinates
    {
        $raw = Str::before(Str::after($this->attributes['coordinates'], 'POINT('), ')');

        [$latitude, $longitude] = explode(' ', $raw);

        return new Coordinates(new Latitude($latitude), new Longitude($longitude));
    }

    /**
     * @return LocationEntity
     */
    public function toEntity(): LocationEntity
    {
        return new LocationEntity([
            'id' => $this->id,
            'coordinates' => $this->coordinates->toArray(),
        ]);
    }
}
