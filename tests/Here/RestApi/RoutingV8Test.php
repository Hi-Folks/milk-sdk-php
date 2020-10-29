<?php

namespace HiFolks\Milk\Tests\Here\RestApi;

use HiFolks\Milk\Here\RestApi\RoutingV8;
use PHPUnit\Framework\TestCase;

class RoutingV8Test extends TestCase
{
    public function testBasicRouting()
    {
        $routing = RoutingV8::instance()
            ->startingPoint(52.5160, 13.3779)
            ->destination(52.5185, 13.4283)
            ->byCar();
        $url = "https://router.hereapi.com/v8/routes?transportMode=car&origin=52.516,13.3779&destination=52.5185,13.4283";
        $this->assertSame($url, $routing->getUrl(), "Routing: Basic GET car");

        $routing = RoutingV8::instance()
            ->startingPoint(52.5160, 13.3779)
            ->destination(52.5185, 13.4283)
            ->byBicycle();
        $url = "https://router.hereapi.com/v8/routes?transportMode=bicycle&origin=52.516,13.3779&destination=52.5185,13.4283";
        $this->assertSame($url, $routing->getUrl(), "Routing: Basic GET bicycle");

        $routing = RoutingV8::instance()
            ->startingPoint(52.5160, 13.3779)
            ->destination(52.5185, 13.4283)
            ->byScooter();
        $url = "https://router.hereapi.com/v8/routes?transportMode=scooter&origin=52.516,13.3779&destination=52.5185,13.4283";
        $this->assertSame($url, $routing->getUrl(), "Routing: Basic GET scooter");

        $routing = RoutingV8::instance()
            ->startingPoint(52.5160, 13.3779)
            ->destination(52.5185, 13.4283)
            ->byFoot();
        $url = "https://router.hereapi.com/v8/routes?transportMode=pedestrian&origin=52.516,13.3779&destination=52.5185,13.4283";
        $this->assertSame($url, $routing->getUrl(), "Routing: Basic GET pedestrian");

        $routing = RoutingV8::instance()
            ->startingPoint(52.5160, 13.3779)
            ->destination(52.5185, 13.4283)
            ->byTruck();
        $url = "https://router.hereapi.com/v8/routes?transportMode=truck&origin=52.516,13.3779&destination=52.5185,13.4283";
        $this->assertSame($url, $routing->getUrl(), "Routing: Basic GET track");

        $routing = RoutingV8::instance()
            ->startingPoint(52.5160, 13.3779)
            ->destination(52.5185, 13.4283)
            ->byBicycle()->langIta();
        $url = "https://router.hereapi.com/v8/routes?transportMode=bicycle&lang=it-IT&origin=52.516,13.3779&destination=52.5185,13.4283";
        $this->assertSame($url, $routing->getUrl(), "Routing: Basic GET bicycle, ita lang");


        $routing = RoutingV8::instance()
            ->startingPoint(52.5160, 13.3779)
            ->destination(52.5185, 13.4283)
            ->byBicycle()
            ->alternatives(3)
            ->langIta();
        $url = "https://router.hereapi.com/v8/routes?transportMode=bicycle&lang=it-IT&alternatives=3&origin=52.516,13.3779&destination=52.5185,13.4283";
        $this->assertSame($url, $routing->getUrl(), "Routing: Basic GET bicycle, ita lang, 3 alternatives");

        $routing = RoutingV8::instance()
            ->startingPoint(52.5160, 13.3779)
            ->destination(52.5185, 13.4283)
            ->unitsImperial();
        $url = "https://router.hereapi.com/v8/routes?units=imperial&origin=52.516,13.3779&destination=52.5185,13.4283";
        $this->assertSame($url, $routing->getUrl(), "Routing: Basic GET units=imperial");

        $routing = RoutingV8::instance()
            ->startingPoint(52.5160, 13.3779)
            ->destination(52.5185, 13.4283)
            ->unitsMetric();
        $url = "https://router.hereapi.com/v8/routes?units=metric&origin=52.516,13.3779&destination=52.5185,13.4283";
        $this->assertSame($url, $routing->getUrl(), "Routing: Basic GET units=metric");

        $routing = RoutingV8::instance()
            ->startingPoint(52.5160, 13.3779)
            ->destination(52.5185, 13.4283)
            ->return("summary");
        $url = "https://router.hereapi.com/v8/routes?return=summary&origin=52.516,13.3779&destination=52.5185,13.4283";
        $this->assertSame($url, $routing->getUrl(), "Routing: Basic GET return summery");

        $routing = RoutingV8::instance()
            ->startingPoint(52.5160, 13.3779)
            ->destination(52.5185, 13.4283)
            ->routingModeFast();
        $url = "https://router.hereapi.com/v8/routes?routingMode=fast&origin=52.516,13.3779&destination=52.5185,13.4283";
        $this->assertSame($url, $routing->getUrl(), "Routing: Basic GET routing mode fast");

        $routing = RoutingV8::instance()
            ->startingPoint(52.5160, 13.3779)
            ->destination(52.5185, 13.4283)
            ->routingModeShort();
        $url = "https://router.hereapi.com/v8/routes?routingMode=short&origin=52.516,13.3779&destination=52.5185,13.4283";
        $this->assertSame($url, $routing->getUrl(), "Routing: Basic GET routing mode short");

        $routing = RoutingV8::instance()
            ->startingPoint(52.5160, 13.3779)
            ->destination(52.5185, 13.4283)
            ->returnAppend("summary")
            ->returnAppend("actions");
        $url = "https://router.hereapi.com/v8/routes?return=summary%2Cactions&origin=52.516,13.3779&destination=52.5185,13.4283";
        $this->assertSame($url, $routing->getUrl(), "Routing: Basic GET routing return summary+actions (append)");

        $routing = RoutingV8::instance()
            ->startingPoint(52.5160, 13.3779)
            ->destination(52.5185, 13.4283)
            ->returnInstructions();
        $url = "https://router.hereapi.com/v8/routes?return=polyline%2Cactions%2Cinstructions&origin=52.516,13.3779&destination=52.5185,13.4283";
        $this->assertSame($url, $routing->getUrl(), "Routing: Basic GET routing return instructions");

        
    }


}
