<?php

namespace HiFolks\Milk\Here\Xyz\Common;

use HiFolks\Milk\Here\Common\ApiConfig;
use HiFolks\Milk\Utils\Environment;

class XyzConfig extends ApiConfig
{


    private const HOST_PROD = "https://xyz.api.here.com";
    private const HOST_STAGE = "https://xyz.cit.api.here.com";
    private const HOST_NONE = "";


    /**
     * XyzConfig constructor.
     * @param string $apiToken
     * @param string $hostname
     * @param string $env
     */
    public function __construct($apiToken = "", $hostname = "", $env = self::ENV_CUSTOM)
    {
        parent::__construct($apiToken, "", "");
        $this->setEnvironmentAndHostname($env, $hostname);
    }

    /**
     * @param string $apiToken
     * @param string $hostname
     * @param string $env
     * @return ApiConfig|XyzConfig|null
     */
    public static function getInstance($apiToken = "", $hostname = "", $env = self::ENV_CUSTOM)
    {
        return new XyzConfig($apiToken, $hostname, $env);
    }


    /**
     * @param string $environment
     * @param string $hostname
     * @return bool
     */
    public function setEnvironmentAndHostname($environment = self::ENV_CUSTOM, $hostname = ""): bool
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
            if ($hostname === "") {
                $this->hostname = Environment::getEnv("XYZ_API_HOSTNAME", self::HOST_PROD);
            } else {
                $this->hostname =  $hostname;
            }
            $retVal = true;
        } else {
            $this->hostname = self::HOST_NONE;
        }
        return $retVal;
    }
}
