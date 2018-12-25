<?php

namespace App\Models\Builders;

use App\Models\Tables\Constant;
use Core\ValueObjects\Coordinates;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class LocationBuilder.
 *
 * @package App\Models\Builders
 */
class LocationBuilder extends Builder
{
    /**
     * @var string
     */
    private $locationsTable = Constant::TABLE_LOCATIONS;

    /**
     * Note: Laravel does not support spatial types.
     * See: https://dev.mysql.com/doc/refman/5.7/en/spatial-type-overview.html
     *
     * @return $this
     */
    public function defaultSelect()
    {
        $coordinates = "ST_AsText({$this->locationsTable}.coordinates) AS coordinates";

        return $this->addSelect("{$this->locationsTable}.*", $this->getConnection()->raw($coordinates));
    }

    /**
     * @param Coordinates $southWest
     * @param Coordinates $northEast
     * @return $this
     */
    public function whereInArea(Coordinates $southWest, Coordinates $northEast)
    {
        $boundaries = sprintf(
            "ST_GeomFromText('POLYGON((%s %s, %s %s, %s %s, %s %s, %s %s))')",
            // South west point.
            $southWest->getLatitude(),
            $southWest->getLongitude(),
            // South east point.
            $southWest->getLatitude(),
            $northEast->getLongitude(),
            // North east point.
            $northEast->getLatitude(),
            $northEast->getLongitude(),
            // North west point.
            $northEast->getLatitude(),
            $southWest->getLongitude(),
            // South west point.
            $southWest->getLatitude(),
            $southWest->getLongitude()
        );

        return $this->whereRaw($this->getConnection()->raw("ST_Contains({$boundaries}, {$this->locationsTable}.coordinates)"));
    }
}
