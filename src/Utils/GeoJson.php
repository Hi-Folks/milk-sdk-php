<?php

namespace HiFolks\Milk\Utils;

use GeoJson\Feature\Feature;
use GeoJson\Feature\FeatureCollection;
use GeoJson\Geometry\LineString;
use GeoJson\Geometry\Point;
use Heremaps\FlexiblePolyline\FlexiblePolyline;

class GeoJson
{
    /**
     * @var array<mixed>
     */
    private $featureCollection = [];

    public function __construct()
    {
        $this->featureCollection = [];
    }

    public function addPolygonFromExtendedPolyline(string $polyline)
    {
        try {
            $data = FlexiblePolyline::decode($polyline);
            $arrayPolygon = [];
            foreach ($data["polyline"] as $p) {
                $arrayPolygon[] = [$p[1], $p[0]];
            }
            $polygon = new LineString($arrayPolygon);
            $f = new Feature($polygon, [], null);
            $this->featureCollection[] = $f;
        } catch (\Exception $e) {
        }
    }


    /**
     * @param float $latitude
     * @param float $longitude
     * @param array<mixed> $properties
     * @param mixed $id
     * @return void
     */
    public function addPoint(float $latitude, float $longitude, $properties = null, $id = null): void
    {
        $point = new Point([ $longitude , $latitude]);
        $f = new Feature($point, $properties, $id);
        $this->featureCollection[] = $f;
    }

    /**
     * @return string
     */
    public function getString(): string
    {
        $fs = new FeatureCollection($this->featureCollection);
        return json_encode($fs);
    }

    /**
     * @param int $idx
     * @param bool $jsonEncoded
     * @return string|mixed
     */
    public function get($idx = 0, $jsonEncoded = true)
    {
        $item = $this->featureCollection[$idx];
        if ($jsonEncoded) {
            return json_encode($item);
        }
        return $item;
    }
}
