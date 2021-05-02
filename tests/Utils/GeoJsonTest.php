<?php

namespace HiFolks\Milk\Tests\Utils;

use HiFolks\Milk\Utils\GeoJson;
use PHPUnit\Framework\TestCase;

/**
 * Class GeoJsonTest
 * @package HiFolks\Milk\Tests\Utils
 */
class GeoJsonTest extends TestCase
{


    public function testConfig(): void
    {
        $gj = new GeoJson();
        $this->assertIsString($gj->getString(), "Check if is string");
        $emptyGeojson = '{"type":"FeatureCollection","features":[]}';
        $this->assertEquals($emptyGeojson, $gj->getString(), "Check if the geojson string is empty");
        $gj->addPoint(45, 30, ["field1" => "value1", "field2" => "value2"]);
        $itemString = $gj->get(0);
        $this->assertIsString($itemString, "Check get item Geojson as string");
        $item = $gj->get(0, false);
        $this->assertEquals(
            45,
            $item->getGeometry()->getCoordinates()[1],
            "Check if the geojson has the right content"
        );
    }
}
