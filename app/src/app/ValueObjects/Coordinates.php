<?php

namespace App\ValueObjects;

/**
 * Class Coordinates.
 *
 * @package App\ValueObjects
 */
final class Coordinates
{
    private $latitude;
    private $longitude;

    /**
     * Coordinates constructor.
     *
     * @param Latitude $latitude
     * @param Longitude $longitude
     */
    public function __construct(Latitude $latitude, Longitude $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    /**
     * @return Latitude
     */
    public function getLatitude(): Latitude
    {
        return $this->latitude;
    }

    /**
     * @return Longitude
     */
    public function getLongitude(): Longitude
    {
        return $this->longitude;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return sprintf('%s %s', $this->getLatitude(), $this->getLongitude());
    }
}
