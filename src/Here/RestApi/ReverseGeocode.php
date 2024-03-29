<?php

namespace HiFolks\Milk\Here\RestApi;

use HiFolks\Milk\Here\RestApi\Common\RestClient;
use HiFolks\Milk\Here\RestApi\Common\RestConfig;

/**
 * Class ReverseGeocode
 * @package HiFolks\Milk\Here\RestApi
 *
 * Doc:
 * https://developer.here.com/documentation/geocoding-search-api/dev_guide/topics/endpoint-reverse-geocode-brief.html
 *
 * API:  https://developer.here.com/documentation/geocoding-search-api/api-reference-swagger.html
 *
 */
class ReverseGeocode extends RestClient
{
    private const BASE_URL = "https://revgeocode.search.hereapi.com";
    /**
     * Specify the center of the search context expressed as coordinates.
     * Format: {latitude},{longitude}
     * Type: {decimal},{decimal}
     * Example: -13.163068,-72.545128 (Machu Picchu Mountain, Peru)
     * @var array<float>
     */
    private $paramAt;


    /**
     * Maximum number of results to be returned.
     * Default is 1
     * @var int
     */
    private $paramLimit;

    /**
     * @var string
     */
    private $paramLang;

    private const ENV_REV_GEOCODE = "ENV_REV_GEOCODE";


    /**
     * ReverseGeocode constructor.
     * @param RestConfig|string|null $c
     */
    public function __construct($c = null)
    {
        parent::__construct($c);
        $this->reset();
    }

    /**
     * @param string $xyzToken
     * @return ReverseGeocode
     */
    public static function instance($xyzToken = ""): self
    {
        return new ReverseGeocode(new RestConfig($xyzToken));
    }

    public function getHostname(): string
    {
        return self::BASE_URL;
    }



    public function reset()
    {
        parent::reset();

        $this->paramAt = [];
        $this->paramLimit = -1;
        $this->paramLang = "";
    }





    /**
     * @param float $latitude
     * @param float $longitude
     * @return self
     */
    public function at($latitude, $longitude): self
    {
        $this->paramAt = [$latitude, $longitude];
        return $this;
    }

    /**
     * @param int $limit
     * @return self
     */
    public function limit($limit = 1): self
    {
        $this->paramLimit = $limit;
        return $this;
    }

    /**
     * @param string $lang
     * @return self
     */
    public function lang($lang): self
    {
        $this->paramLang = $lang;
        return $this;
    }

    /**
     * @return self
     */
    public function langIta(): self
    {
        return $this->lang("it-IT");
    }

    protected function queryString(): string
    {
        $retString = "";
        if (sizeof($this->paramAt) === 2) {
            $retString = $this->addQueryParam($retString, "at", $this->paramAt[0] . "," . $this->paramAt[1]);
        }
        if ($this->paramLimit > 0) {
            $retString = $this->addQueryParam($retString, "limit", $this->paramLimit);
        }
        if ($this->paramLang !== "") {
            $retString = $this->addQueryParam($retString, "lang", $this->paramLang);
        }


        $retString = $this->makeCredentialQueryParams($retString);

        return $retString;
    }


    protected function getPath(): string
    {
        return "/v1/revgeocode";
    }
}
