<?php

namespace HiFolks\Milk\Here\RestApi\Common;

use HiFolks\Milk\Here\Common\ApiConfig;

class RestConfig extends ApiConfig
{






    public static function getInstance($apiToken = "", $hostname = "", $env = self::ENV_CUSTOM)
    {
        if (self::$instance == null) {
            self::$instance = new RestConfig($apiToken, $hostname, $env);
        }
        return self::$instance;
    }
}
