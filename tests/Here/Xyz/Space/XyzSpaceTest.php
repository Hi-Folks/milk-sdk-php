<?php

namespace HiFolks\Milk\Tests\Here\Xyz\Space;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use HiFolks\Milk\Here\Xyz\Common\XyzConfig;
use HiFolks\Milk\Here\Xyz\Space\XyzSpace;
use PHPUnit\Framework\TestCase;

class XyzSpaceTest extends TestCase
{



    public function testSpace(): void
    {
        $host = "http://localhost:8080";
        $config = XyzConfig::getInstance("", $host);
        $space = new XyzSpace($config);
        $this->assertSame($host . "/hub/spaces", $space->getUrl(), "Space: Basic GET");

        $spaceId = "1234";
        $space = XyzSpace::instance()->spaceId($spaceId);
        $space = (new XyzSpace($config))->spaceId($spaceId);
        $this->assertSame($host . "/hub/spaces/" . $spaceId, $space->getUrl(), "Space: Basic GET");


        $space = (new XyzSpace($config))->ownerAll();
        $this->assertSame($host . "/hub/spaces?owner=" . urlencode("*"), $space->getUrl(), "Space: Basic GET");

        $space = (new XyzSpace($config))->ownerOthers();
        $this->assertSame($host . "/hub/spaces?owner=others", $space->getUrl(), "Space: Basic GET");

        $ownerId = "OWNERID";
        $space = (new XyzSpace($config))->ownerSomeOther($ownerId);
        $this->assertSame($host . "/hub/spaces?owner=" . $ownerId, $space->getUrl(), "Space: Basic GET");

        $space = (new XyzSpace($config))->ownerMe();
        $this->assertSame($host . "/hub/spaces?owner=me", $space->getUrl(), "Space: Basic GET");

        $limit = 3;
        $space = (new XyzSpace($config))->ownerMe()->limit($limit);
        $this->assertSame($host . "/hub/spaces?limit=" . $limit . "&owner=me", $space->getUrl(), "Space: Basic GET");

        $space = (new XyzSpace($config))->includeRights();
        $this->assertSame($host . "/hub/spaces?includeRights=true", $space->getUrl(), "Space: include rights");
    }

    public function testManageSpace(): void
    {
        $spaceTitle = "My Space";
        $spaceDescription = "Description";

        $responseString = '{
            "id": "x-demospace",
            "owner":"{appId}",
            "title": "' . $spaceTitle . '",
            "description": "' . $spaceDescription . '"
        }';
        $responseString40x = '{
          "type": "ErrorResponse",
          "streamId": "7480e28a-e273-11e8-9af8-7508bbe361d9",
          "error": "Exception",
          "errorMessage": "Invalid request details"
        }';

        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], $responseString),
            new Response(400, ['content-type' => 'application/json'], $responseString40x),
            new Response(401, ['content-type' => 'application/json'], $responseString40x)
        ]);
        $handlerStack = HandlerStack::create($mock);


        $spaceTitle = "My Space";
        $spaceDescription = "Description";
        $host = "http://localhost:8080";
        $config = XyzConfig::getInstance("", $host);
        $space = new XyzSpace($config);
        $space->setHandler($handlerStack);
        // 200
        $response = $space->create($spaceTitle, $spaceDescription);

        $this->assertSame(false, $response->isError(), "Check Response from create");
        $this->assertSame($spaceTitle, $response->getDataObject()->title, "Check created space title");
        $this->assertSame(
            $spaceDescription,
            $response->getDataObject()->description,
            "Check created space description"
        );
        $this->assertStringContainsString("title", $response->getDataAsJsonString(), "Check getDataAsJsonString");
        // 400
        $response = $space->create($spaceTitle, $spaceDescription);
        $this->assertSame(true, $response->isError(), "Check Response from create");
        $this->assertStringContainsString("400", $response->getErrorMessage(), "Error message for 400");
        // 401
        $response = $space->create($spaceTitle, $spaceDescription);
        $this->assertSame(true, $response->isError(), "Check Response from create");
        $this->assertStringContainsString("401", $response->getErrorMessage(), "Error message for 401");
    }
}
