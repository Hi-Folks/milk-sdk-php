<?php

namespace HiFolks\Milk\Here\RestApi;

use HiFolks\Milk\Here\RestApi\Common\RestClient;
use HiFolks\Milk\Here\RestApi\Common\RestConfig;
use HiFolks\Milk\Here\Common\LatLong;

/**
 * Class Discover
 * @package HiFolks\Milk\Here\RestApi
 */
class Discover extends RestClient
{

    private const BASE_URL = "https://discover.search.hereapi.com";

    private ?LatLong $paramAt = null;
    private string $paramQ = "";
    private ?string $paramIn = null;
    private ?int $paramLimit = null;
    private ?string $paramLang = null;


    /**
     * Discover constructor.
     * @param RestConfig|string|null $c
     */
    public function __construct($c = null)
    {
        parent::__construct($c);
        $this->reset();
    }


    public static function instance(string $apiKey = ""): Discover
    {
        $rc = new RestConfig();
        $rc->setApiKey($apiKey);
        return new Discover($rc);
    }

    public function getHostname(): string
    {
        return self::BASE_URL;
    }

    public function reset()
    {
        parent::reset();
        $this->contentType = "";
        $this->acceptContentType = "";
        $this->paramAt = null;
        $this->paramIn = null;
        $this->paramLang = null;
        $this->paramLimit = null;
        $this->paramQ = "";
    }

    /**
     * Specify the center of the search context expressed as coordinates
     */
    public function at(float $latitude, float $longitude): Discover
    {
        return $this->atLatLong(new LatLong($latitude, $longitude));
    }
    public function atLatLong(LatLong $latLong): Discover
    {
        $this->paramAt = $latLong;
        return $this;
    }
    /**
     * A free-text address/place query.
     * It could be an address or a place
     * For example: "125, Berliner, berlin"
     * or "Beacon, Boston, Hospital"
     * or "Schnurrbart German Pub and Restaurant, Hong Kong"
     */
    public function q(string $query): Discover
    {
        $this->paramQ = $query;
        return $this;
    }
    /**
     * Search within a geographic area. This is a hard filter.
     * Results will be returned if they are located within the specified area.
     * A geographic area can be a country:
     * - $country = "USA"
     * - $country = "USA,ITA"
     */
    public function inCountry(string $country): Discover
    {
        $this->paramIn = "countryCode:" . $country;
        return $this;
    }
    /**
     * Search within a geographic area. This is a hard filter.
     * Results will be returned if they are located within the specified area.
     * A geographic area can be a country:
     * - $lat , $lng : the center of the area
     * - $radius: the radius in meters. Default 10000 meters (10 kms)
     */
    public function inCircleArea(float $lat, float $lng, int $radius = 10000): Discover
    {
        $this->paramIn = sprintf("%s:%f,%f;r=%u", "circle", $lat, $lng, $radius);
        return $this;
    }

    /**
     * Maximum number of results to be returned.
     */
    public function limit(int $limit): Discover
    {
        $this->paramLimit = $limit;
        return $this;
    }
    /**
     * Select the language to be used for result rendering from a list of BCP 47 compliant language codes..
     */
    public function lang(string $lang): Discover
    {
        $this->paramLang = $lang;
        return $this;
    }




    protected function queryString(): string
    {
        $retString = "";
        if (! is_null($this->paramAt)) {
            $retString = $this->addQueryParam($retString, "at", $this->paramAt->getString(), false);
        }
        if ($this->paramQ !== "") {
            $retString = $this->addQueryParam($retString, "q", $this->paramQ);
        }

        if (! is_null($this->paramIn)) {
            $retString = $this->addQueryParam($retString, "in", $this->paramIn);
        }
        if (! is_null($this->paramLimit)) {
            $retString = $this->addQueryParam($retString, "limit", $this->paramLimit);
        }
        if (! is_null($this->paramLang)) {
            $retString = $this->addQueryParam($retString, "lang", $this->paramLang);
        }


        $retString = $this->makeCredentialQueryParams($retString);

        return $retString;
    }



    protected function getPath(): string
    {
        return "/v1/discover";
    }
}
