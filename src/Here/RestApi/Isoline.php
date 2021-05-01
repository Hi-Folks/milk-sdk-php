<?php

namespace HiFolks\Milk\Here\RestApi;

use HiFolks\Milk\Here\RestApi\Common\RestClient;
use HiFolks\Milk\Here\RestApi\Common\RestConfig;
use HiFolks\Milk\Here\Common\LatLong;

/**
 * Class Isoline
 * @package HiFolks\Milk\Here\RestApi
 */
class Isoline extends RestClient
{

    private const BASE_URL = "https://isoline.router.hereapi.com";

    /**
     * @var LatLong|null
     */
    private $paramOrigin = null;

    /**
     * @var string Specifies the time of departure.
     */
    private $paramDepartureTime;

    /**
     * @var LatLong|null
     */
    private $paramDestination = null;

    /**
     * @var string Specifies the time of arrival.
     */
    private $paramArrivalTime;

    /**
     * @var mixed[]
     */
    private $paramRange;

    /**
     * Enum [ fast | short ]
     * @var string Specifies which optimization is applied during route calculation.
     */
    private $paramRoutingMode;

    /**
     * Mode of transport to be used for the calculation of the route.
     * Enum [car | pedestrian | truck]
     * @var string
     */
    private $paramTransportMode;

    /**
     * Limits the number of points in the resulting isoline geometry
     * @var int
     */
    private $paramShapeMaxPoints;

    /**
     * Specifies how isoline calculation is optimized.
     * Enum [balanced | quality | performance]
     * default: balanced
     * @var string
     */
    private $paramOptimizeFor;


    private const ENV_ISOLINE = "ENV_ISOLINE";


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
     * @return Isoline
     */
    public static function instance($xyzToken = ""): self
    {
        return new Isoline(new RestConfig($xyzToken));
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

        $this->paramDepartureTime = "";
        $this->paramArrivalTime = "";
        $this->paramRange = [];
        $this->paramShapeMaxPoints = -1;
        $this->paramOptimizeFor = "";
        $this->paramTransportMode = "car";
        $this->paramDepartureTime = "";
        $this->paramRoutingMode = "";
        $this->paramOrigin = null;
        $this->paramDestination = null;
    }

    /**
     * Set the origin point.
     * Center of the isoline request.
     * The Isoline(s) will cover the region which can be reached from this point
     * within given range. It cannot be used in combination with destination parameter.
     * @param float $latitude
     * @param float $longitude
     * @return $this
     */
    public function originPoint(float $latitude, float $longitude): self
    {
        return $this->originLatLong(new LatLong($latitude, $longitude));
    }

    /**
     * @param LatLong $latLong
     * @return $this
     */
    public function originLatLong(LatLong $latLong): self
    {
        $this->paramOrigin = $latLong;
        return $this;
    }




    /**
     * Specifies the time of departure as defined
     * by either date-time or full-date T partial-time in RFC 3339,
     * section 5.6 (for example, 2019-06-24T01:23:45).
     * @param string $departureTime
     * @return self
     */
    public function departureTime(string $departureTime): self
    {
        $this->paramDepartureTime = $departureTime;
        return $this;
    }

    /**
     * Center of the isoline request.
     * The Isoline(s) will cover the region within the specified range that can reach this point.
     * It cannot be used in combination with origin parameter.
     * @param float $latitude
     * @param float $longitude
     * @return $this
     */
    public function destination(float $latitude, float $longitude): self
    {
        return $this->destinationLatLong(new LatLong($latitude, $longitude));
    }
    /**
     * @param LatLong $latLong
     * @return $this
     */
    public function destinationLatLong(LatLong $latLong): self
    {
        $this->paramDestination = $latLong;
        return $this;
    }

    /**
     * Specifies the time of arrival as defined
     * by either date-time or full-date T partial-time in RFC 3339,
     * section 5.6 (for example, 2019-06-24T01:23:45).
     * @param string $arrivalTime
     * @return self
     */
    public function arrivalTime(string $arrivalTime): self
    {
        $this->paramArrivalTime = $arrivalTime;
        return $this;
    }

    /**

     * @param int|array<int> $values
     * @param string $type ["time", "distance", "consumption"]
     * @return self
     */
    public function range($values, $type = "time"): self
    {
        if (! is_array($values)) {
            $value = $values;
            $values = [];
            $values[] = $value;
        }
        $this->paramRange = [$values, $type ];
        return $this;
    }

    /**
     * @param int|array<int> $values
     * @return self
     */
    public function rangeByTime($values): self
    {
        return $this->range($values, "time");
    }

    /**
     * @param int|array<int> $values
     * @return self
     */
    public function rangeByDistance($values): self
    {
        return $this->range($values, "distance");
    }

    /**
     * @param int|array<int> $values
     * @return self
     */
    public function rangeByConsumption($values): self
    {
        return $this->range($values, "consumption");
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

    private function shape(int $maxPoints): self
    {
        $this->paramShapeMaxPoints = $maxPoints;
        return $this;
    }

    /**
     * @param string $optimizedFor
     * @return self
     */
    private function optimizedFor(string $optimizedFor): self
    {
        $this->paramOptimizeFor = $optimizedFor;
        return $this;
    }
    /**
     * Calculation of isoline focuses on quality, that is,
     * the graph used for isoline calculation has higher granularity generating an isoline that is more precise
     * @return self
     */
    private function optimizedForQuality(): self
    {
        return $this->optimizedFor("quality");
    }
    /**
     * Calculation of isoline is performance-centric,
     * quality of isoline is reduced to provide better performance.
     * @return self
     */
    private function optimizedForPerformance(): self
    {
        return $this->optimizedFor("performance");
    }
    /**
     * Calculation of isoline takes a balanced approach averaging between
     * quality and performance.
     * @return self
     */
    private function optimizedForBalanced(): self
    {
        return $this->optimizedFor("balanced");
    }




    protected function queryString(): string
    {
        $retString = "";
        if (! is_null($this->paramOrigin)) {
            $retString = $this->addQueryParam($retString, "origin", $this->paramOrigin->getString(), false);
        }
        if ($this->paramDepartureTime !== "") {
            $retString = $this->addQueryParam($retString, "departureTime", $this->paramDepartureTime);
        }
        if (! is_null($this->paramDestination)) {
            $retString = $this->addQueryParam($retString, "destination", $this->paramDestination->getString(), false);
        }
        if ($this->paramArrivalTime !== "") {
            $retString = $this->addQueryParam($retString, "arrivalTime", $this->paramArrivalTime);
        }
        if (is_array($this->paramRange) && sizeof($this->paramRange) === 2) {
            $retString = $this->addQueryParam($retString, "range[type]", $this->paramRange[1]);
            $retString = $this->addQueryParam($retString, "range[values]", implode(",", $this->paramRange[0]));
        }
        if ($this->paramRoutingMode !== "") {
            $retString = $this->addQueryParam($retString, "routingMode", $this->paramRoutingMode);
        }
        if ($this->paramTransportMode !== "") {
            $retString = $this->addQueryParam($retString, "transportMode", $this->paramTransportMode);
        } else {
            $retString = $this->addQueryParam($retString, "transportMode", "car");
        }
        if ($this->paramShapeMaxPoints > 0) {
            $retString = $this->addQueryParam($retString, "shape[maxPoints]", $this->paramShapeMaxPoints);
        }
        if ($this->paramOptimizeFor !== "") {
            $retString = $this->addQueryParam($retString, "optimizeFor", $this->paramOptimizeFor);
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
        return "/v8/isolines";
    }
}
