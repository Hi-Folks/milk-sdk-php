<?php

namespace HiFolks\Milk\Here\RestApi;

use HiFolks\Milk\Here\Common\Bbox;
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
     * Specifies which optimization is applied during route calculation.
     * Enum [ fast | short ]
     */
    private string $paramRoutingMode;

    /**
     *
     * Mode of transport to be used for the calculation of the route.
     * Enum [car | pedestrian | truck | bicycle | scooter ]
     */
    private string $paramTransportMode;

    /**
     * Array of features that routes will avoid.
     * Values: [ seasonalClosure | tollRoad | controlledAccessHighway | ferry |
     *      carShuttleTrain | tunnel | dirtRoad | difficultTurns ]
     * @var array<string>
     */
    private array $paramAvoidFeatures;

    /**
     * Array of user defined areas that routes will avoid to go through
     * @var array<Bbox>
     */
    private array $paramAvoidAreas;

    /**
     * @var array<string>
     */
    private array $paramReturn;

    private string $paramLang;

    /**
     * Number of alternative routes to return aside from the optimal route.
     */
    private int $paramAlternatives;

    /**
     * Departure Time
     * https://www.php.net/manual/en/datetime.formats.php
     */
    private string $paramDepartureTime;

    /**
     * Units of measurement used in guidance instructions. The default is metric.
     * Enum: "metric" "imperial"
     */
    private string $paramUnits;

    /**
     * @var array<LatLong>
     */
    private array $paramVia = [];

    private ?LatLong $origin = null;

    private string $originAddress = "";

    private ?LatLong $destination = null;

    private string $destinationAddress = "";

    private bool $enableGeocoding = false;

    private const ENV_ROUTING_V8 = "ENV_ROUTING_V8";

    private const DEFAULT_TRANSPORT_MODE = "car";


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
     * @param string $apiToken
     * @return RoutingV8
     */
    public static function instance($apiToken = ""): self
    {
        $rc = new RestConfig();
        $rc->setApiKey($apiToken);
        return new RoutingV8($rc);
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
        $this->paramTransportMode = self::DEFAULT_TRANSPORT_MODE;
        $this->paramRoutingMode = "";
        $this->paramReturn = [];
        $this->paramLang = "";
        $this->paramAlternatives = -1;
        $this->paramUnits = "";
        $this->paramVia = [];
        $this->paramAvoidFeatures = [];
        $this->paramAvoidAreas = [];

        $this->paramDepartureTime = "";

        $this->origin = null;
        $this->destination = null;
    }

    /**
     * @param string $mode
     * @return self
     */
    private function routingMode(string $mode): self
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

    /**
     * @param string $mode
     * @return self
     */
    private function transportMode(string $mode): self
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

    /**
     * @param string $returnString
     * @return self
     */
    public function return(string $returnString): self
    {
        $this->paramReturn = [];
        $this->paramReturn[] = $returnString;
        return $this;
    }

    /**
     * @param string $returnString
     * @return self
     */
    public function returnAppend(string $returnString): self
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

    public function returnActions(): self
    {
        $this->paramReturn[] = "actions";
        return $this;
    }

    /**
     * @param string $lang
     * @return self
     */
    public function lang(string $lang): self
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

    /**
     * Set departure time
     * The accepted format is exlpained here:
     * https://www.php.net/manual/en/datetime.formats.php
     * @param string $time
     * @return $this
     */
    public function departureTime(string $time): self
    {
        $this->paramDepartureTime = $time;
        return $this;
    }

    /**
     * @param float $latitude
     * @param float $longitude
     * @return $this
     */
    public function via(float $latitude, float $longitude): self
    {
        $this->paramVia = [];
        $this->paramVia[] = new LatLong($latitude, $longitude);

        return $this;
    }
    public function viaAppend(float $latitude, float $longitude): self
    {
        $this->paramVia[] = new LatLong($latitude, $longitude);

        return $this;
    }

    /**
     * A feature or list of features for route to avoid
     *
     * @param string|array<string> $feature feature or features to avoid
     * @return $this
     */
    public function avoidFeatures($feature): self
    {
        //$this->paramAvoidFeatures = [];

        if (is_string($feature)) {
            $feature = [$feature];
        }
        $this->paramAvoidFeatures = array_merge($this->paramAvoidFeatures, $feature);
        return $this;
    }
    public function avoidTollRoad(): self
    {
        return $this->avoidFeatures("tollRoad");
    }
    public function avoidFerry(): self
    {
        return $this->avoidFeatures("ferry");
    }
    public function avoidSeasonalClosure(): self
    {
        return $this->avoidFeatures("seasonalClosure");
    }
    public function avoidControlledAccessHighway(): self
    {
        return $this->avoidFeatures("controlledAccessHighway");
    }
    public function avoidCarShuttleTrain(): self
    {
        return $this->avoidFeatures("carShuttleTrain");
    }
    public function avoidTunnel(): self
    {
        return $this->avoidFeatures("tunnel");
    }
    public function avoidDirtRoad(): self
    {
        return $this->avoidFeatures("dirtRoad");
    }
    public function avoidDifficultTurns(): self
    {
        return $this->avoidFeatures("difficultTurns");
    }

    /**
     * A rectangular area on earth to avoid
     *
     * @param float $westLongitude Longitude value of the westernmost point of the area.
     * @param float $southLatitude Latitude value of the southernmost point of the area.
     * @param float $eastLongitude Longitude value of the easternmost point of the area.
     * @param float $northLatitude Latitude value of the northernmost point of the area.
     * @return $this
     */
    public function avoidArea(
        float $westLongitude,
        float $southLatitude,
        float $eastLongitude,
        float $northLatitude
    ): self {

        $this->paramAvoidAreas[] = new Bbox($westLongitude, $southLatitude, $eastLongitude, $northLatitude);
        return $this;
    }

    /**
     * @param float $latitude
     * @param float $longitude
     * @param int $distance
     * @return $this
     */
    public function avoidAreaByCenter(
        float $latitude,
        float $longitude,
        $distance = 1
    ): self {
        $this->paramAvoidAreas[] = Bbox::createByCenter($latitude, $longitude, $distance);
        return $this;
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

    public function enableGeocoding(bool $enable = true): self
    {
        $this->enableGeocoding = $enable;
        return $this;
    }

    public function originAddress(string $address): self
    {
        $this->originAddress = $address;
        return $this;
    }
    public function destinationAddress(string $address): self
    {
        $this->destinationAddress = $address;
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
            $retString = $this->addQueryParam($retString, "return", implode(",", $this->paramReturn), false);
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
        if ($this->paramDepartureTime !== "") {
            $retString = $this->addQueryParam(
                $retString,
                "departureTime",
                date("Y-m-d\TH:i:sP", strtotime($this->paramDepartureTime)),
                false
            );
        }
        if (is_array($this->paramAvoidFeatures) && count($this->paramAvoidFeatures) > 0) {
            $retString = $this->addQueryParam(
                $retString,
                "avoid[features]",
                implode(",", $this->paramAvoidFeatures),
                false
            );
        }

        if (is_array($this->paramAvoidAreas) && count($this->paramAvoidAreas) > 0) {
            $retString = $this->addQueryParam($retString, "avoid[areas]", implode("|", $this->paramAvoidAreas), false);
        }

        foreach ($this->paramVia as $viaValue) {
            $retString = $this->addQueryParam($retString, "via", $viaValue->getString(), false);
        }

        $retString = $this->makeCredentialQueryParams($retString);

        return $retString;
    }

    /**
     * @return mixed
     */
    public function getDefaultActions()
    {
        $this->returnInstructions();
        $result = $this->get();
        if ($result->isError()) {
            return [];
        }
        try {
            return $result->getData()->routes[0]->sections[0]->actions;
        } catch (\Exception $e) {
            return [];
        }
    }

    public function get()
    {

        if (! $this->enableGeocoding) {
            // maybe forcing enableGeocoding
            $this->enableGeocoding =
                (is_null($this->origin) && $this->originAddress !== "")
                &&
                (is_null($this->destination) && $this->destinationAddress !== "");
        }

        if ($this->enableGeocoding) {
            if ($this->originAddress !== "") {
                $g = Geocode::instance($this->getConfig()->getCredentials()->getApiKey())
                    ->q($this->originAddress)
                    ->get();
                if (! $g->isError()) {
                    $place = $g->getData()->items[0];
                    $this->origin = new LatLong($place->position->lat, $place->position->lng);
                }
            }
            if ($this->destinationAddress !== "") {
                $g = Geocode::instance($this->getConfig()->getCredentials()->getApiKey())
                    ->q($this->destinationAddress)
                    ->get();
                if (! $g->isError()) {
                    $place = $g->getData()->items[0];
                    $this->destination = new LatLong($place->position->lat, $place->position->lng);
                }
            }
        }
        return parent::get();
    }
    /**
     * @return mixed
     */
    public function getRoute()
    {
        $result = $this->get();
        if ($result->isError()) {
            return [];
        }
        return $result->getData()->routes[0];
    }

    protected function getPath(): string
    {
        return "/v8/routes";
    }
}
