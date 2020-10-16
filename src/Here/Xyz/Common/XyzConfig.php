<?php

namespace HiFolks\Milk\Here\Xyz\Common;

use HiFolks\Milk\Here\Common\ApiConfig;

class XyzConfig extends ApiConfig
{


    private const HOST_PROD = "https://xyz.api.here.com";
    private const HOST_STAGE = "https://xyz.cit.api.here.com";
    private const HOST_NONE = "";


    public static function getInstance($apiToken = "", $hostname = "", $env = self::ENV_CUSTOM)
    {
        if (self::$instance == null) {
            self::$instance = new XyzConfig($apiToken, $hostname, $env);
        }
        return self::$instance;
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




}
