<?php

namespace HiFolks\Milk\Here\RestApi\Common;

use HiFolks\Milk\Here\Common\ApiConfig;

class RestConfig extends ApiConfig
{

    public function __construct($apiToken = "", $hostname = "", $env = self::ENV_CUSTOM)
    {
        parent::__construct($apiToken, $hostname, $env);
    }

    public static function getInstance($apiToken = "", $hostname = "", $env = self::ENV_CUSTOM)
    {
        return new RestConfig($apiToken, $hostname, $env);
    }
}
