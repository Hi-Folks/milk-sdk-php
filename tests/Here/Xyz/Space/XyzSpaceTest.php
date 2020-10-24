<?php

use HiFolks\Milk\Here\Xyz\Common\XyzConfig;
use HiFolks\Milk\Here\Xyz\Space\XyzSpace;
use PHPUnit\Framework\TestCase;


class XyzSpaceTest extends TestCase
{


    public function testSpace()
    {
        $host = "http://localhost:8080";
        $config = XyzConfig::getInstance("",$host);
        $space = new XyzSpace($config);
        $this->assertSame($host."/hub/spaces", $space->getUrl(), "Space: Basic GET");

        $spaceId= "1234";
        $space = (new XyzSpace($config))->spaceId($spaceId);
        $this->assertSame($host."/hub/spaces/".$spaceId, $space->getUrl(), "Space: Basic GET");


        $space = (new XyzSpace($config))->ownerAll();
        $this->assertSame($host."/hub/spaces?owner=".urlencode("*"), $space->getUrl(), "Space: Basic GET");

        $space = (new XyzSpace($config))->ownerOthers();
        $this->assertSame($host."/hub/spaces?owner=others", $space->getUrl(), "Space: Basic GET");

        $ownerId="OWNERID";
        $space = (new XyzSpace($config))->ownerSomeOther($ownerId);
        $this->assertSame($host."/hub/spaces?owner=".$ownerId, $space->getUrl(), "Space: Basic GET");

        $space = (new XyzSpace($config))->ownerMe();
        $this->assertSame($host."/hub/spaces?owner=me", $space->getUrl(), "Space: Basic GET");

        $limit=3;
        $space = (new XyzSpace($config))->ownerMe()->limit($limit);
        $this->assertSame($host."/hub/spaces?limit=".$limit."&owner=me", $space->getUrl(), "Space: Basic GET");

    }
}
