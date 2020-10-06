<?php

namespace HiFolks\Milk\Here\RestApi\Common;

class ApiConfig
{
    /**
     * @var null
     */
    private static $instance = null;

    /**
     * @var ApiCredentials
     */
    private $credentials;
    /**
     * @var mixed|string
     */
    private $hostname = "";
    /**
     * @var string
     */
    private $environment = "";

    /**
     *
     */
    private const ENV_PROD = "PRD";
    /**
     *
     */
    private const ENV_STAGE = "STAGE";
    /**
     *
     */
    private const ENV_CUSTOM = "CUSTOM";




    /**
     * Return the hostname of https://developer.here.com/develop/rest-apis
     * @return string
     */
    public function getHostname(): string
    {
        return $this->hostname;
    }

    public function setEnvironment($environment = self::ENV_CUSTOM): bool
    {
        $this->environment = $environment;
        return true;
    }

    /**
     * @return ApiCredentials
     */
    public function getCredentials(): ApiCredentials
    {
        return $this->credentials;
    }

    private function __construct($apiToken = "", $hostname = "", $env = self::ENV_CUSTOM)
    {
        // Setting things:
        $this->setEnvironment($env);
        $this->hostname = $hostname;
        $this->credentials = ApiCredentials::token($apiToken);
    }

    public static function getInstance($apiToken = "", $hostname = "", $env = self::ENV_CUSTOM)
    {
        if (self::$instance == null) {
            self::$instance = new ApiConfig($apiToken, $hostname, $env);
        }
        return self::$instance;
    }

    public function setToken(string $token): bool
    {
        $this->credentials->setAccessToken($token);
        return true;
    }

    public function setAppIdAppCode(string $appId, string $appCode): bool
    {
        $this->credentials->setAppIdAppCode($appId, $appCode);
        return true;
    }
}
