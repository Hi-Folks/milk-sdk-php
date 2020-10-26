<?php

namespace HiFolks\Milk\Here\RestApi;

use HiFolks\Milk\Here\RestApi\Common\RestClient;
use HiFolks\Milk\Here\RestApi\Common\RestConfig;

/**
 * Class Geocode
 * @package HiFolks\Milk\Here\RestApi
 *
 * https://developer.here.com/documentation/geocoding-search-api/api-reference-swagger.html
 *
 */
class Geocode extends RestClient
{
    private const BASE_URL = "https://geocode.search.hereapi.com";
    /**
     * Specify the center of the search context expressed as coordinates.
     * Format: {latitude},{longitude}
     * Type: {decimal},{decimal}
     * Example: -13.163068,-72.545128 (Machu Picchu Mountain, Peru)
     * @var array
     */
    private $paramAt;

    /**
     * Search within a geographic area.
     * Format: countryCode:{countryCode}[,{countryCode}]*
     * Examples: countryCode:USA or countryCode:CAN,MEX,USA
     * @var string
     */
    private $paramIn;

    /**
     * Maximum number of results to be returned.
     * @var int
     */
    private $paramLimit;

    private $paramQ;

    private $paramQq;

    private $paramLang;

    private const ENV_GEOCODE = "ENV_GEOCODE";


    /**
     * Geocode constructor.
     * @param RestConfig|string|null $c
     */
    public function __construct($c = null)
    {
        parent::__construct($c);
        $this->reset();
    }

    /**
     * @param string $xyzToken
     * @return Geocode
     */
    public static function instance($xyzToken = ""): self
    {
        return new Geocode(new RestConfig($xyzToken));
    }

    public function getHostname(): string
    {
        return self::BASE_URL;
    }



    public function reset()
    {
        parent::reset();

        $this->paramAt = [];
        $this->paramIn = "";
        $this->paramLimit = -1;
        $this->paramQ = "";
        $this->paramQq = [];
        $this->paramLang = "";
    }






    public function at($latitude, $longitude): self
    {
        $this->paramAt = [$latitude, $longitude];
        return $this;
    }

    public function in($countryCode): self
    {
        if (is_array($countryCode)) {
            $this->paramIn = "countryCode:" . implode(",", $countryCode);
        } else {
            $this->paramIn = "countryCode:" . $countryCode;
        }
        return $this;
    }

    public function limit($limit = 20): self
    {
        $this->paramLimit = $limit;
        return $this;
    }

    public function q($freeTextQuery = "Berlin"): self
    {
        $this->paramQ = $freeTextQuery;
        return $this;
    }

    public function country($country): self
    {
        $this->paramQq["country"] = $country;
        return $this;
    }
    public function state($state): self
    {
        $this->paramQq["state"] = $state;
        return $this;
    }
    public function city($city): self
    {
        $this->paramQq["city"] = $city;
        return $this;
    }
    public function district($district): self
    {
        $this->paramQq["district"] = $district;
        return $this;
    }
    public function street($street): self
    {
        $this->paramQq["street"] = $street;
        return $this;
    }
    public function houseNumber($houseNumber): self
    {
        $this->paramQq["houseNumber"] = $houseNumber;
        return $this;
    }
    public function postalCode($postalCode): self
    {
        $this->paramQq["postalCode"] = $postalCode;
        return $this;
    }
    public function lang($lang): self
    {
        $this->paramLang = $lang;
        return $this;
    }
    public function langIta(): self
    {
        return $this->lang("it-IT");
    }

    protected function queryString(): string
    {
        $retString = "";


        if ($this->paramIn !== "") {
            $retString = $this->addQueryParam($retString, "in", $this->paramIn);
        }
        if (sizeof($this->paramAt) === 2) {
            $retString = $this->addQueryParam($retString, "at", $this->paramAt[0] . "," . $this->paramAt[1]);
        }
        if ($this->paramLimit > 0) {
            $retString = $this->addQueryParam($retString, "limit", $this->paramLimit);
        }
        if ($this->paramQ !== "") {
            $retString = $this->addQueryParam($retString, "q", $this->paramQ);
        }
        if (is_array($this->paramQq) && sizeof($this->paramQq) > 0) {
            $separator = "";
            $qqString = "";
            foreach ($this->paramQq as $key => $value) {
                $qqString = $qqString . $separator . $key . "=" . $value;
                $separator = ";";
            }
            $retString = $this->addQueryParam($retString, "qq", $qqString);
        }
        if ($this->paramLang !== "") {
            $retString = $this->addQueryParam($retString, "lang", $this->paramLang);
        }


        $cred = $this->c->getCredentials();
        if (! $cred->isBearer()) {
            if ($cred->getApiKey() !== "") {
                $retString = $this->addQueryParam($retString, "apiKey", $cred->getApiKey());
            }
            if ($cred->getAppId() !== "" && $cred->getAppCode() !== "") {
                $retString = $this->addQueryParam($retString, "app_id", $cred->getAppId());
                $retString = $this->addQueryParam($retString, "app_code", $cred->getAppCode());
            }
        }

        return $retString;
    }


    protected function getPath(): string
    {
        return "/v1/geocode";
    }
}
