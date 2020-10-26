<?php

use HiFolks\Milk\Here\Xyz\Common\XyzConfig;
use PHPUnit\Framework\TestCase;


class XyzConfigTest extends TestCase
{


    public function testConfig()
    {
        $host = "http://localhost";
        $token = "a";
        $config = XyzConfig::getInstance($token, $host);

        $this->assertIsString($config->getHostname(), "Checking Hostname type");
        $this->assertIsString($config->getCredentials()->getAccessToken(), "Checking Access Token type");

        $this->assertSame($host, $config->getHostname(), "Checking Hostname");
        $this->assertSame($token, $config->getCredentials()->getAccessToken(),  "Checking Access Token");

    }

    public function testSetEnvAndHost()
    {
        $config = new XyzConfig();
        $config->setEnvironmentAndHostname("PRD");
        $this->assertSame("https://xyz.api.here.com", $config->getHostname(), "Checking Hostname");

        $config->setEnvironmentAndHostname("STAGE");
        $this->assertSame("https://xyz.cit.api.here.com", $config->getHostname(), "Checking Hostname");

        $_ENV["XYZ_API_HOSTNAME"] = "http://localhost:8081";
        $config->setEnvironmentAndHostname("CUSTOM", "");
        $this->assertSame("http://localhost:8081", $config->getHostname(), "Checking Hostname");

        $config->setEnvironmentAndHostname("XXX", "");
        $this->assertSame("", $config->getHostname(), "Checking Hostname");

    }
}
