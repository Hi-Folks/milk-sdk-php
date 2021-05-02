<?php

namespace HiFolks\Milk\Here\RestApi\Common;

use HiFolks\Milk\Here\Common\ApiConfig;

class RestConfig extends ApiConfig
{
    /**
     * @param string $apiToken
     * @param string $hostname
     * @param string $env
     */
    public function __construct($apiToken = "", $hostname = "", $env = self::ENV_CUSTOM)
    {
        //parent::__construct($apiToken, $hostname, $env);
        $this->initApiConfig($apiToken, $hostname, $env);
    }

    /**
     * @param string $apiToken
     * @param string $hostname
     * @param string $env
     * @return self
     */
    public static function getInstance($apiToken = "", $hostname = "", $env = self::ENV_CUSTOM): self
    {
        return new RestConfig($apiToken, $hostname, $env);
    }
}
