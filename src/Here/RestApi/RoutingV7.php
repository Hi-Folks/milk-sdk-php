<?php

namespace HiFolks\Milk\Here\RestApi;

use HiFolks\Milk\Here\RestApi\Common\ApiClient;
use HiFolks\Milk\Here\RestApi\Common\ApiConfig;
use HiFolks\Milk\Here\RestApi\Common\LatLong;

/**
 * Class RoutingV7
 * @package Rbit\Milk\HRestApi\Routing
 */
class RoutingV7 extends ApiClient
{
    /**
     * @var string
     */
    private $format = "json";

    /**
     *  RoutingType relevant to calculation.
     * Enum [ fastest | shortest | balanced ]
     * @var string
     */
    private $paramModeType;
    /**
     * Specify which mode of transport to calculate the route for.
     * Enum [car | pedestrian | carHOV | publicTransport | publicTransportTimeTable | truck | bicycle
     * @var string
     */
    private $paramModeTransport;


    /**
     * @var LatLong|null
     */
    private $paramWaypoint0 = null;

    /**
     * @var LatLong|null
     */
    private $paramWaypoint1 = null;


    private const ENV_WEATHER = "ENV_WEATHER_PROD";


    public function __construct()
    {
        $this->reset();
    }

    public static function instance($apiToken = ""): self
    {
        $hostname = "https://route.ls.hereapi.com";
        $routing = self::config(ApiConfig::getInstance($apiToken, $hostname, self::ENV_WEATHER));
        return $routing;
    }

    public static function config(ApiConfig $c): self
    {
        $routing = new self();
        $routing->c = $c;
        return $routing;
    }

    public static function setToken(string $token): self
    {
        $routing = self::config(ApiConfig::getInstance());
        $routing->c->setToken($token);
        return $routing;
    }

    public function reset()
    {
        parent::reset();
        $this->format = "json";

        $this->paramModeTransport = "car";
        $this->paramModeType = "fastest";

        $this->paramWaypoint0 = null;
        $this->paramWaypoint1 = null;
    }






    public function typeFastest(): self
    {
        $this->paramModeType = "fastest";
        return $this;
    }
    public function byFoot(): self
    {

        $this->paramModeTransport = "pedestrian";
        return $this;
    }
    public function byCar(): self
    {
        $this->paramModeTransport = "car";
        return $this;
    }


    public function startingPoint(float $latitude, float $longitude): self
    {
        $this->paramWaypoint0 = new LatLong($latitude, $longitude);
        return $this;
    }

    public function startingPointLatLong(LatLong $latLong): self
    {
        $this->paramWaypoint0 = $latLong;
        return $this;
    }
    public function destination(float $latitude, float $longitude): self
    {
        $this->paramWaypoint1 = new LatLong($latitude, $longitude);
        return $this;
    }
    public function destinationLatLong(LatLong $latLong): self
    {
        $this->paramWaypoint1 = $latLong;
        return $this;
    }



    protected function queryString(): string
    {
        $retString = "";

        $paramMode = [$this->paramModeType, $this->paramModeTransport];
        $retString = $this->addQueryParam($retString, "mode", implode(";", $paramMode), false);


        if ($this->paramWaypoint0) {
            $retString = $this->addQueryParam($retString, "waypoint0", $this->paramWaypoint0->getString(), false);
        }
        if ($this->paramWaypoint1) {
            $retString = $this->addQueryParam($retString, "waypoint1", $this->paramWaypoint1->getString(), false);
        }

        $retString = $this->addQueryParam($retString, "apiKey", $this->c->getCredentials()->getAccessToken());


        return $retString;
    }

    public function getManeuverInstructions()
    {
        $array = [];
        $result = $this->get();
        return $result->response->route[0]->leg[0]->maneuver;
    }

    protected function getPath(): string
    {
        $retPath = "";
        $retPath = "/routing/7.2/calculateroute." . $this->format;
        return $retPath;
    }
}
