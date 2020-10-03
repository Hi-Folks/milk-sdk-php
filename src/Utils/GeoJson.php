<?php

namespace HiFolks\Milk\Utils;

class GeoJson
{
    private array $featureCollection = [];


    public function addPoint($latitude, $longitude, $properties = null, $id = null)
    {
        $point = new \GeoJson\Geometry\Point([ $longitude , $latitude]);
        $f = new \GeoJson\Feature\Feature($point, $properties, $id);
        $this->featureCollection[] = $f;
    }

    public function getString()
    {
        $fs = new \GeoJson\Feature\FeatureCollection($this->featureCollection);
        return json_encode($fs);
    }

    public function get($idx = 0, $jsonEncoded = true)
    {
        $item = $this->featureCollection[$idx];
        if ($jsonEncoded) {
            return json_encode($item);
        }
        return $item;
    }
}
