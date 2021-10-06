<?php

namespace HiFolks\Milk\Tests\Here\RestApi;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use HiFolks\Milk\Here\RestApi\Common\RestConfig;
use HiFolks\Milk\Here\RestApi\MapImage;
use HiFolks\Milk\Here\RestApi\RoutingV8;
use PHPUnit\Framework\TestCase;

class MapImageTest extends TestCase
{
    public function testBasicRouting(): void
    {
        $baseUrlRoute = "https://image.maps.ls.hereapi.com/mia/1.6/mapview";
        $hereApiKey = "XYZ";
        $image = MapImage::instance($hereApiKey)
            ->center(45.548, 11.54947)
            ->addPoi(45, 12, "ff0000")
            ->addPoi(45.1, 12.1, "00ff00")
            ->addPoi(45.2, 12.2, "0000ff", "", "12", "Test 3")
            ->zoom(12)
            ->height(2048)
            ->width(intval(2048 / 1.4));
        $url =
            $baseUrlRoute . "?c=45.548,11.54947&w=1462&h=2048&z=12" .
        "&poix0=45%2C12%3Bff0000%3B%3B%3B%3B&poix1=45.1%2C12.1%3B00ff00%3B%3B%3B%3B" .
        "&poix2=45.2%2C12.2%3B0000ff%3B%3B12%3BTest+3%3B&apiKey=XYZ";
        $this->assertSame(
            $url,
            $image->getUrl(),
            "Static Image, get URL"
        );
    }
}
