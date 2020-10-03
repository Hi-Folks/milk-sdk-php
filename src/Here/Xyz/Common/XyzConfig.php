<?php

namespace HiFolks\Milk\Here\Xyz\Common;

class XyzConfig
{
    private static $instance = null;

    private XyzCredentials $credentials;
    private string $hostname = self::HOST_PROD;
    private string $environment = self::ENV_PROD;

    private const ENV_PROD = "PRD";
    private const ENV_STAGE = "STAGE";
    private const ENV_CUSTOM = "CUSTOM";
    private const ENV_NONE = "";
    private const HOST_PROD = "https://xyz.api.here.com";
    private const HOST_STAGE = "https://xyz.cit.api.here.com";
    private const HOST_NONE = "";




    /**
     * Return the hostname of Xyz API HUB
     * @return string
     */
    public function getHostname(): string
    {
        return $this->hostname;
    }

    public function setEnvironment($environment = self::ENV_CUSTOM): bool
    {
        $retVal = false;
        $this->environment = $environment;
        if ($this->environment === self::ENV_PROD) {
            $this->hostname = self::HOST_PROD;
            $retVal = true;
        } elseif ($this->environment === self::ENV_STAGE) {
            $this->hostname = self::HOST_STAGE;
            $retVal = true;
        } elseif ($this->environment === self::ENV_CUSTOM) {
            $this->hostname = getenv("XYZ_API_HOSTNAME") ?: self::HOST_PROD;
            $retVal = true;
        } else {
            $this->hostname = self::HOST_NONE;
        }


        return $retVal;
    }

    /**
     * @return XyzCredentials
     */
    public function getCredentials(): XyzCredentials
    {
        return $this->credentials;
    }

    private function __construct($xyzToken = "", $env = self::ENV_CUSTOM)
    {
        // Setting things:
        $this->setEnvironment($env);
        $this->credentials = XyzCredentials::token($xyzToken);
    }

    public static function getInstance($xyzToken = "", $env = self::ENV_CUSTOM)
    {
        if (self::$instance == null) {
            self::$instance = new XyzConfig($xyzToken, $env);
        }
        return self::$instance;
    }

    public function setToken(string $token): bool
    {
        $this->credentials->setAccessToken($token);
        return true;
    }
}
