<?php

namespace HiFolks\Milk\Here\Xyz\Space;

use HiFolks\Milk\Here\Xyz\Common\XyzClient;
use HiFolks\Milk\Here\Xyz\Common\XyzConfig;

/**
 * Class XyzSpace
 * @package HiFolks\Milk\Xyz
 */
class XyzSpaceStatistics extends XyzClient
{


    private bool $paramSkipCache = false;



    public function __construct()
    {
        $this->reset();
    }

    public static function instance($xyzToken = ""): XyzSpaceStatistics
    {
        $space = XyzSpaceStatistics::config(XyzConfig::getInstance($xyzToken));
        return $space;
    }

    public static function config(XyzConfig $c): XyzSpaceStatistics
    {
        $space = new XyzSpaceStatistics();
        $space->c = $c;
        return $space;
    }

    public static function setToken(string $token): XyzSpaceStatistics
    {
        $space = XyzSpaceStatistics::config(XyzConfig::getInstance());
        $space->c->setToken($token);
        return $space;
    }

    public function reset()
    {
        parent::reset();

        $this->paramSkipCache = false;
        $this->spaceId = "";
    }

    public function statistics(): XyzSpaceStatistics
    {
        $this->setType(self::API_TYPE_STATISTICS);
        $this->contentType = "application/json";
        return $this;
    }

    /**
     * Set the space id in the API
     * @param string $id
     * @return $this
     */
    public function spaceId(string $id): XyzSpaceStatistics
    {
        $this->spaceId = $id;
        $this->setType(self::API_TYPE_STATISTICS);
        $this->contentType = "application/json";
        return $this;
    }

    /**
     * If set to true the response is not returned from cache. Default is false.
     * @return $this
     */
    public function skipCache(bool $skipcache = true): XyzSpaceStatistics
    {
        $this->paramSkipCache = $skipcache;
        return $this;
    }


    protected function queryString(): string
    {
        $retString = "";
        if ($this->paramSkipCache) {
            $retString = $this->addQueryParam($retString, "skipCache", "true");
        }
        return $retString;
    }
}
