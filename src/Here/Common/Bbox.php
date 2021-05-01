<?php

namespace HiFolks\Milk\Here\Common;

class Bbox
{
    /**
     * @var LatLong
     */
    private $southWest;
    /**
     * @var LatLong
     */
    private $northEast;


    public function __construct(
        float $longitudeWest,
        float $latitudeSouth,
        float $longitudeEast,
        float $latitudeNorth
    ) {
        $this->southWest = new LatLong($latitudeSouth, $longitudeWest);
        $this->northEast = new LatLong($latitudeNorth, $longitudeEast);
    }

    public static function createByCenter(float $latitude, float $longitude, float $distance = 1): Bbox
    {
        $radius = $distance / 6371.01; //6371.01 is the earth radius in KM
        $minLat = $latitude - $radius;
        $maxLat = $latitude + $radius;
        $deltaLon = asin(sin($radius) / cos($latitude));
        $minLon = $longitude - $deltaLon;
        $maxLon = $longitude + $deltaLon;
        return new self($maxLon, $minLat, $minLon, $maxLat);
    }

    public function getWestLongitude(): float
    {
        return $this->southWest->getLongitude();
    }
    public function getSouthLatitude(): float
    {
        return $this->southWest->getLatitude();
    }
    public function getEastLongitude(): float
    {
        return $this->northEast->getLongitude();
    }
    public function getNorthLatitude(): float
    {
        return $this->northEast->getLatitude();
    }


    public function setSouthWest(float $latitude, float $longitude): void
    {
        $this->southWest->setLatLong($latitude, $longitude);
    }
    public function setNorthEast(float $latitude, float $longitude): void
    {
        $this->northEast->setLatLong($latitude, $longitude);
    }




    public function getString(): string
    {
        return "bbox:" .
            $this->getWestLongitude() . "," .
            $this->getSouthLatitude() . "," .
            $this->getEastLongitude() . "," .
            $this->getNorthLatitude();
    }

    public function __toString(): string
    {
        return $this->getString();
    }
}
