<?php

namespace HiFolks\Milk\Here\RestApi;

use HiFolks\Milk\Here\RestApi\Common\RestClient;
use HiFolks\Milk\Here\RestApi\Common\RestConfig;
use HiFolks\Milk\Here\Common\LatLong;

/**
 * Class RoutingV8
 * @package HiFolks\Milk\Here\RestApi
 */
class RoutingV8 extends RestClient
{

    private const BASE_URL = "https://router.hereapi.com";
    /**
     *  @property string $routingMode Specifies which optimization is applied during route calculation.
     * Enum [ fast | short ]
     */
    private $paramRoutingMode;
    /**
     *
     * Mode of transport to be used for the calculation of the route.
     * Enum [car | pedestrian | truck | bicycle | scooter ]
     */
    private $paramTransportMode;


    /**
     * @var array
     */
    private $paramReturn;

    /**
     * @var string
     */
    private $paramLang;

    /**
     * @var int
     * Number of alternative routes to return aside from the optimal route.
     *
     */
    private $paramAlternatives;

    /**
     * @var string
     * Units of measurement used in guidance instructions. The default is metric.
     * Enum: "metric" "imperial"
     */
    private $paramUnits;



    /**
     * @var LatLong|null
     */
    private $origin = null;
    /**
     * @var LatLong|null
     */
    private $destination = null;


    private const ENV_ROUTING_V8 = "ENV_ROUTING_V8";


    /**
     * RoutingV8 constructor.
     * @param RestConfig|string|null $c
     */
    public function __construct($c = null)
    {
        parent::__construct($c);
        $this->reset();
    }

    /**
     * @param string $xyzToken
     * @return RoutingV8
     */
    public static function instance($xyzToken = ""): self
    {
        return new RoutingV8(new RestConfig($xyzToken));
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
        $this->paramTransportMode = "";
        $this->paramRoutingMode = "";
        $this->paramReturn = [];
        $this->paramLang = "";
        $this->paramAlternatives = -1;
        $this->paramUnits = "";

        $this->origin = null;
        $this->destination = null;
    }




    private function routingMode($mode): self
    {
        $this->paramRoutingMode = $mode;
        return $this;
    }
    /**
     * Route calculation from start to destination optimized by travel time
     */
    public function routingModeFast(): self
    {
        return $this->routingMode("fast");
    }
    /**
     * Route calculation from start to destination disregarding any speed information
     * @return self
     */
    public function routingModeShort(): self
    {
        return $this->routingMode("short");
    }


    private function transportMode($mode): self
    {
        $this->paramTransportMode = $mode;
        return $this;
    }
    public function byFoot(): self
    {
        return $this->transportMode("pedestrian");
    }
    public function byCar(): self
    {
        return $this->transportMode("car");
    }
    public function byTruck(): self
    {
        return $this->transportMode("truck");
    }
    public function byBicycle(): self
    {
        return $this->transportMode("bicycle");
    }
    public function byScooter(): self
    {
        return $this->transportMode("scooter");
    }


    public function return($returnString): self
    {
        $this->paramReturn = [];
        $this->paramReturn[] = $returnString;
        return $this;
    }
    public function returnAppend($returnString): self
    {
        $this->paramReturn[] = $returnString;
        return $this;
    }


    public function returnInstructions(): self
    {
        $this->paramReturn[] = "polyline";
        $this->paramReturn[] = "actions";
        $this->paramReturn[] = "instructions";
        return $this;
    }
    public function lang($lang): self
    {
        $this->paramLang = $lang;
        return $this;
    }

    /**
     * @param int $alternatives
     * @return $this
     */
    public function alternatives(int $alternatives): self
    {
        $this->paramAlternatives = $alternatives;
        return $this;
    }

    /**
     * @param string $units
     * @return $this
     */
    public function units(string $units): self
    {
        $this->paramUnits = $units;
        return $this;
    }
    public function unitsMetric(): self
    {
        return $this->units("metric");
    }
    public function unitsImperial(): self
    {
        return $this->units("imperial");
    }



    public function langIta(): self
    {
        return $this->lang("it-IT");
    }




    public function startingPoint(float $latitude, float $longitude): self
    {
        return $this->startingPointLatLong(new LatLong($latitude, $longitude));
    }

    public function startingPointLatLong(LatLong $latLong): self
    {
        $this->origin = $latLong;
        return $this;
    }
    public function destination(float $latitude, float $longitude): self
    {
        return $this->destinationLatLong(new LatLong($latitude, $longitude));
    }
    public function destinationLatLong(LatLong $latLong): self
    {
        $this->destination = $latLong;
        return $this;
    }



    protected function queryString(): string
    {
        $retString = "";


        if ($this->paramRoutingMode) {
            $retString = $this->addQueryParam($retString, "routingMode", $this->paramRoutingMode);
        }
        if ($this->paramTransportMode) {
            $retString = $this->addQueryParam($retString, "transportMode", $this->paramTransportMode);
        }

        if (count($this->paramReturn) > 0) {
            $retString = $this->addQueryParam($retString, "return", implode(",", $this->paramReturn));
        }

        if ($this->paramLang !== "") {
            $retString = $this->addQueryParam($retString, "lang", $this->paramLang);
        }
        if ($this->paramAlternatives >= 0) {
            $retString = $this->addQueryParam($retString, "alternatives", $this->paramAlternatives);
        }
        if ($this->paramUnits !== "") {
            $retString = $this->addQueryParam($retString, "units", $this->paramUnits);
        }
        if ($this->origin) {
            $retString = $this->addQueryParam($retString, "origin", $this->origin->getString(), false);
        }
        if ($this->destination) {
            $retString = $this->addQueryParam($retString, "destination", $this->destination->getString(), false);
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

    public function getDefaultActions()
    {
        $result = $this->get();
        if ($result->isError()) {
            return [];
        }
        return $result->getData()->routes[0]->sections[0]->actions;
    }

    protected function getPath(): string
    {
        return "/v8/routes";
    }
}
