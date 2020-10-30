<?php

namespace HiFolks\Milk\Here\Common;

class ApiConfig
{
    /**
     * @var ApiConfig|null
     */
    protected static $instance = null;

    /**
     * @var ApiCredentials
     */
    protected $credentials;
    /**
     * @var mixed|string
     */
    protected $hostname = "";
    /**
     * @var string
     */
    protected $environment = "";

    /**
     *
     */
    protected const ENV_PROD = "PRD";
    /**
     *
     */
    protected const ENV_STAGE = "STAGE";
    /**
     *
     */
    protected const ENV_CUSTOM = "CUSTOM";




    /**
     * Return the hostname of https://developer.here.com/develop/rest-apis
     * @return string
     */
    public function getHostname(): string
    {
        return $this->hostname;
    }

    /**
     * @param string $hostname
     * @return void
     */
    public function setHostname(string $hostname): void
    {
        $this->hostname = $hostname;
    }

    /**
     * @param string $environment
     * @return bool
     */
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

    /**
     * @param string $apiToken
     * @param string $hostname
     * @param string $env
     */
    public function __construct($apiToken = "", $hostname = "", $env = self::ENV_CUSTOM)
    {
        // Setting things:

        $this->setHostname($hostname);
        $this->setEnvironment($env);
        $this->credentials = new ApiCredentials($apiToken);
    }

    /**
     * @param string $apiToken
     * @param string $hostname
     * @param string $env
     * @return self
     */
    public static function getInstance($apiToken = "", $hostname = "", $env = self::ENV_CUSTOM): self
    {
        if (self::$instance == null) {
            self::$instance = new self($apiToken, $hostname, $env);
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

    public function setApiKey(string $apiKey): bool
    {
        $this->credentials->setApiKey($apiKey);
        return true;
    }
}
