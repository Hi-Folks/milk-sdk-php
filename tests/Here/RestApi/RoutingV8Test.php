<?php

namespace HiFolks\Milk\Tests\Here\RestApi;

use HiFolks\Milk\Here\RestApi\RoutingV8;
use PHPUnit\Framework\TestCase;

class RoutingV8Test extends TestCase
{
    public function testBasicRouting()
    {
        $routing = RoutingV8::instance()
            ->byCar();
        $url = "https://router.hereapi.com/v8/routes?routingMode=fast&transportMode=car";
        $this->assertSame($url, $routing->getUrl(), "Routing: Basic GET car");

        $routing = RoutingV8::instance()
            ->byBicycle();
        $url = "https://router.hereapi.com/v8/routes?routingMode=fast&transportMode=bicycle";
        $this->assertSame($url, $routing->getUrl(), "Routing: Basic GET bicycle");

        $routing = RoutingV8::instance()
            ->byScooter();
        $url = "https://router.hereapi.com/v8/routes?routingMode=fast&transportMode=scooter";
        $this->assertSame($url, $routing->getUrl(), "Routing: Basic GET scooter");

        $routing = RoutingV8::instance()
            ->byFoot();
        $url = "https://router.hereapi.com/v8/routes?routingMode=fast&transportMode=pedestrian";
        $this->assertSame($url, $routing->getUrl(), "Routing: Basic GET pedestrian");

        $routing = RoutingV8::instance()
            ->byTruck();
        $url = "https://router.hereapi.com/v8/routes?routingMode=fast&transportMode=truck";
        $this->assertSame($url, $routing->getUrl(), "Routing: Basic GET track");

        $routing = RoutingV8::instance()
            ->byBicycle()->langIta();
        $url = "https://router.hereapi.com/v8/routes?routingMode=fast&transportMode=bicycle&lang=it-IT";
        $this->assertSame($url, $routing->getUrl(), "Routing: Basic GET bicycle, ita lang");


        $routing = RoutingV8::instance()
            ->byBicycle()->alternatives(3)->langIta();
        $url = "https://router.hereapi.com/v8/routes?routingMode=fast&transportMode=bicycle&lang=it-IT&alternatives=3";
        $this->assertSame($url, $routing->getUrl(), "Routing: Basic GET bicycle, ita lang, 3 alternatives");

    }


}
