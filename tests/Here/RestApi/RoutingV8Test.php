<?php

namespace HiFolks\Milk\Tests\Here\RestApi;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use HiFolks\Milk\Here\RestApi\Common\RestConfig;
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
        $this->assertSame($url, $routing->getUrl(), "Routing: Basic GET return summary");

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
            ->viaAppend(52.5213, 13.4051);
        $url = "https://router.hereapi.com/v8/routes?origin=52.516,13.3779&destination=52.5185,13.4283&via=52.5213,13.4051";
        $this->assertSame($url, $routing->getUrl(), "Routing: Basic GET routing via");

        $routing = RoutingV8::instance()
            ->startingPoint(52.5160, 13.3779)
            ->destination(52.5185, 13.4283)
            ->returnAppend("summary")
            ->returnAppend("actions");
        $url = "https://router.hereapi.com/v8/routes?return=summary,actions&origin=52.516,13.3779&destination=52.5185,13.4283";
        $this->assertSame($url, $routing->getUrl(), "Routing: Basic GET routing return summary+actions (append)");

        $routing = RoutingV8::instance()
            ->startingPoint(52.5160, 13.3779)
            ->destination(52.5185, 13.4283)
            ->returnInstructions();
        $url = "https://router.hereapi.com/v8/routes?return=polyline,actions,instructions&origin=52.516,13.3779&destination=52.5185,13.4283";
        $this->assertSame($url, $routing->getUrl(), "Routing: Basic GET routing return instructions");

        $routing = RoutingV8::instance()
            ->setAppIdAppCode("aa", "bb")
            ->startingPoint(52.5160, 13.3779)
            ->destination(52.5185, 13.4283)
            ->returnInstructions();
        $url = "https://router.hereapi.com/v8/routes?return=polyline,actions,instructions&origin=52.516,13.3779&destination=52.5185,13.4283&app_id=aa&app_code=bb";
        $this->assertSame($url, $routing->getUrl(), "Routing: Basic GET routing with app_id and app_code");

        $routing = RoutingV8::instance()
            ->setApiKey("xxx")
            ->startingPoint(52.5160, 13.3779)
            ->destination(52.5185, 13.4283)
            ->returnInstructions();
        $url = "https://router.hereapi.com/v8/routes?return=polyline,actions,instructions&origin=52.516,13.3779&destination=52.5185,13.4283&apiKey=xxx";
        $this->assertSame($url, $routing->getUrl(), "Routing: Basic GET routing with apiKey");

        $routing = RoutingV8::instance()
            ->setApiKey("xxx")
            ->startingPoint(52.51375, 13.42462)
            ->destination(52.52332, 13.42800)
            ->via(52.52426,13.43000)
            ->byCar()
            ->return("polyline")
            ->returnAppend("summary");

        $url = "https://router.hereapi.com/v8/routes?transportMode=car&return=polyline,summary&origin=52.51375,13.42462&destination=52.52332,13.428&via=52.52426,13.43&apiKey=xxx";
        $this->assertSame($url, $routing->getUrl(), "Routing: Basic GET routing with via");

        $routing = RoutingV8::instance()
            ->setApiKey("xxx")
            ->startingPoint(52.51375, 13.42462)
            ->destination(52.52332, 13.42800)
            ->via(52.52426,13.43000)
            ->viaAppend(52.53,13.44)
            ->byCar()
            ->return("polyline")
            ->returnAppend("summary");

        $url = "https://router.hereapi.com/v8/routes?transportMode=car&return=polyline,summary&origin=52.51375,13.42462&destination=52.52332,13.428&via=52.52426,13.43&via=52.53,13.44&apiKey=xxx";
        $this->assertSame($url, $routing->getUrl(), "Routing: Basic GET routing with multiple via");

        $routing = RoutingV8::instance()
            ->setApiKey("xxx")
            ->startingPoint(47.257410,11.351458)
            ->destination(47.168076,11.861380)
            ->avoidFeatures("tollRoad")
            ->byCar();

        $url = "https://router.hereapi.com/v8/routes?transportMode=car&origin=47.25741,11.351458&destination=47.168076,11.86138&avoid%5Bfeatures%5D=tollRoad&apiKey=xxx";
        $this->assertSame($url, $routing->getUrl(), "Routing: Basic GET routing avoid Toll");

        $routing2 = RoutingV8::instance()
            ->setApiKey("xxx")
            ->startingPoint(47.257410,11.351458)
            ->destination(47.168076,11.861380)
            ->avoidTollRoad()
            ->byCar();
        $this->assertSame($routing2->getUrl(), $routing->getUrl(), "Routing: Basic GET routing avoid Toll  comparing 2 ways");


        $routing = RoutingV8::instance()
            ->setApiKey("xxx")
            ->startingPoint(47.257410,11.351458)
            ->destination(47.168076,11.861380)
            ->avoidTollRoad()
            ->avoidCarShuttleTrain()
            ->avoidControlledAccessHighway()
            ->avoidDifficultTurns()
            ->avoidDirtRoad()
            ->avoidFerry()
            ->avoidSeasonalClosure()
            ->avoidTunnel()
            ->byCar();

        $url = "https://router.hereapi.com/v8/routes?transportMode=car&origin=47.25741,11.351458&destination=47.168076,11.86138&avoid%5Bfeatures%5D=tollRoad,carShuttleTrain,controlledAccessHighway,difficultTurns,dirtRoad,ferry,seasonalClosure,tunnel&apiKey=xxx";
        $this->assertSame($url, $routing->getUrl(), "Routing: Basic GET routing avoid Everything");


$routing = RoutingV8::instance()
    ->setApiKey("xxx")
    ->startingPoint(47.257410,11.351458)
    ->destination(47.168076,11.861380)
    ->avoidAreaByCenter(52,13, 30)
    ->byFoot();

        $url = "https://router.hereapi.com/v8/routes?transportMode=pedestrian&origin=47.25741,11.351458&destination=47.168076,11.86138&avoid%5Bareas%5D=bbox:12.971105927764,51.995291170474,13.028894072236,52.004708829526&apiKey=xxx";
        $this->assertSame($url, $routing->getUrl(), "Routing: Basic GET routing avoid bbox");


    }

    public function testBasicRoutingWithMock()
    {
        $responseString = '{
  "routes": [
    {
      "id": "1793a897-0843-4957-ab63-c61e0f13aff2",
      "sections": [
        {
          "id": "c3e300ab-a880-4fab-9bb2-702355b2bf6b",
          "type": "vehicle",
          "actions": [
            {
              "action": "depart",
              "duration": 126,
              "instruction": "Head toward Chausseestraße on Invalidenstraße. Go for 1.2 km.",
              "offset": 0
            },
            {
              "action": "arrive",
              "duration": 0,
              "instruction": "Arrive at Invalidenstraße.",
              "offset": 78
            }
          ],
          "departure": {
            "time": "2019-12-05T15:15:56+01:00",
            "place": {
              "type": "place",
              "location": {
                "lat": 52.53100287169218,
                "lng": 13.38464098982513
              }
            }
          },
          "arrival": {
            "time": "2019-12-05T15:18:02+01:00",
            "place": {
              "type": "place",
              "location": {
                "lat": 52.52639072947204,
                "lng": 13.368653766810894
              }
            }
          },
          "summary": {
            "duration": 126,
            "length": 1200
          },
          "polyline": "BG2znmkDi89wZ9ChKAA1IvfAArH5cAArHvbAA1CrJAArF5SAAtP9yBAAT1E3E3QAA_BrH3M9sBAA_F5SAA3KlkBAA1EtNAApB_DAAhC1EAApB1I_D5OAA3ErPAApFtTAAtN_wBAA1GtVAA5U3lCAA_DhOAA3KliBAAtXjvCAArDtLAA1EhQAA1CrJAA_BrFAAvbl9CAAhIvZ_FtTrDtLAAV1I1CtNAA1E3QAArLnoB1G5YAAhGhSpBrFAAhC1GAA1FxT",
          "spans": [
            {
              "offset": 0,
              "names": [
                {
                  "value": "Invalidenstraße",
                  "language": "de"
                }
              ],
              "length": 787
            },
            {
              "offset": 49,
              "names": [
                {
                  "value": "Invalidenstraße",
                  "language": "de"
                },
                {
                  "value": "Sandkrugbrücke",
                  "language": "de"
                }
              ],
              "length": 51
            },
            {
              "offset": 57,
              "names": [
                {
                  "value": "Invalidenstraße",
                  "language": "de"
                }
              ],
              "length": 362
            }
          ],
          "transport": {
            "mode": "car"
          }
        }
      ]
    }
  ]
}';
        $responseString400 = '{
  "title": "Malformed request",
  "status": 400,
  "code": "E605001",
  "cause": "missing field `destination`",
  "action": "",
  "correlationId": "4199533b-6290-41db-8d79-edf4f4019a74"
}';
        $responseString401 = '{
  "error": "Unauthorized",
  "error_description": "No credentials found"
}';
        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], $responseString),
            new Response(400, ['content-type' => 'application/json'], $responseString400),
            new Response(401, ['content-type' => 'application/json'], $responseString401)
        ]);
        $handlerStack = HandlerStack::create($mock);

        $config = RestConfig::getInstance("");
        $routingObject = new RoutingV8($config);
        $routingObject->setHandler($handlerStack);
        $routingActions = $routingObject
            ->startingPoint(52.5160, 13.3779)
            ->destination(52.5185, 13.4283)
            ->byCar()
            ->getDefaultActions();

        $this->assertIsArray($routingActions, "Routing: Basic GET car actions with mock");
        $this->assertSame(2, sizeof($routingActions), "Routing: count actions with mock");

        $routingActions = $routingObject
            ->startingPoint(52.5160, 13.3779)
            ->destination(52.5185, 13.4283)
            ->byCar()
            ->getDefaultActions();

        $this->assertIsArray($routingActions, "Routing: Basic GET car actions with mock");
        $this->assertSame(0, sizeof($routingActions), "Routing: count actions from error with mock");


    }


}
