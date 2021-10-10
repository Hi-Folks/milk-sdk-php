<?php

namespace HiFolks\Milk\Tests\Here\RestApi;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use HiFolks\Milk\Here\RestApi\Common\RestConfig;
use HiFolks\Milk\Here\RestApi\Discover;
use PHPUnit\Framework\TestCase;

class DiscoverTest extends TestCase
{
    public function testBasicDiscover(): void
    {
        $baseUrlDiscover = "https://discover.search.hereapi.com/v1/discover";
        $address = "Colosseo, Roma";
        $discoverUrl = Discover::instance("XYZ")
            ->q($address)
            ->at(41.902782, 12.496366)
            ->inCountry("ITA")
            ->getUrl();
        $url =
            $baseUrlDiscover . "?at=41.902782,12.496366&q=Colosseo%2C+Roma&in=countryCode%3AITA&apiKey=XYZ";
        $this->assertSame(
            $url,
            $discoverUrl,
            "Discover: Basic discovering"
        );
    }
}
