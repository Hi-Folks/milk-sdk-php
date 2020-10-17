<?php

namespace HiFolks\Milk\Here\RestApi;

use HiFolks\Milk\Here\RestApi\Common\RestClient;
use HiFolks\Milk\Here\RestApi\Common\RestConfig;

/**
 * Class ApiWeather
 * @package Rbit\Milk\HRestApi\Weather
 */
class Weather extends RestClient
{

    private const BASE_URL = "https://weather.api.here.com";
    /**
     * @var string
     */
    private $format = "json";
    /**
     * @var string
     */
    private $paramProduct = "observation";
    /**
     * @var string
     */
    private $paramName = "Berlin";
    /**
     * @var string|null
     */
    private $paramZipCode = null;
    /**
     * @var bool|null
     */
    private $paramMetric = null;
    /**
     * @var bool|null
     */
    private $paramOneobservation = null;
    /**
     * @var float|null
     */
    private $paramLatitude = null;
    /**
     * @var float|null
     */
    private $paramLongitude = null;
    /**
     * @var string|null
     */
    private $paramLanguage = null;





    private const ENV_WEATHER = "ENV_WEATHER_PROD";

    // current weather conditions from the eight closest locations to the specified location
    private const PRODUCT_OBSERVATION = "observation";
    // morning, afternoon, evening and night weather forecasts for the next seven days.
    private const PRODUCT_FORECAST_7DAYS = "forecast_7days";
    // daily weather forecasts for the next seven days
    private const PRODUCT_FORECAST_7DAYS_SIMPLE = "forecast_7days_simple";
    // hourly weather forecasts for the next seven days
    private const PRODUCT_FORECAST_HOURLY = "forecast_hourly";
    // information on when the sun and moon rise and set, and on the phase of the moon for the next seven days
    private const PRODUCT_FORECAST_ASTRONOMY = "forecast_astronomy";
    // forecasted weather alerts for the next 24 hours
    private const PRODUCT_ALERTS = "alerts";
    // all active watches and warnings for the US and Canada
    private const PRODUCT_NWS_ALERTS = "nws_alerts";







    public function __construct()
    {
        parent::__construct();
        $this->reset();
    }

    public static function instance($apiToken = ""): self
    {

        $weather = Weather::config(RestConfig::getInstance($apiToken, self::BASE_URL, self::ENV_WEATHER));
        return $weather;
    }

    public static function config(RestConfig $c): self
    {
        $weather = new Weather();
        $weather->c = $c;
        return $weather;
    }

    public static function setToken(string $token): self
    {
        $weather = self::config(RestConfig::getInstance($token, self::BASE_URL, self::ENV_WEATHER));
        $weather->c->setToken($token);
        return $weather;
    }

    public function setAppIdAppCode(string $appId, string $appCode): self
    {
        $weather = self::config(RestConfig::getInstance("", self::BASE_URL, self::ENV_WEATHER));
        $weather->c->setAppIdAppCode($appId, $appCode);
        return $weather;
    }

    public function setApiKey(string $apiKey): self
    {
        $weather = self::config(RestConfig::getInstance("", self::BASE_URL, self::ENV_WEATHER));
        $weather->c->setApiKey($apiKey);
        return $weather;
    }

    public function reset()
    {
        parent::reset();
        $this->acceptContentType = "*";
        $this->contentType = "*";
        $this->format = "json";
        $this->paramName = "Berlin";
        $this->paramProduct = self::PRODUCT_ALERTS;
        $this->paramMetric = null;
        $this->paramOneobservation = null;
        $this->paramZipCode = null;
        $this->paramLatitude = null;
        $this->paramLongitude = null;
        $this->paramLanguage = null;
    }


    /**
     * Set the product for the Weather API
     * A parameter identifying the type of report to obtain.
     * The possible values are as follows:
     * - observation – current weather conditions from the eight closest locations to the specified location
     * - forecast_7days – morning, afternoon, evening and night weather forecasts for the next seven days.
     * - forecast_7days_simple – daily weather forecasts for the next seven days
     * - forecast_hourly – hourly weather forecasts for the next seven days
     * - forecast_astronomy – information on when the sun and moon rise and set,
     * and on the phase of the moon for the next seven days
     * - alerts – forecasted weather alerts for the next 24 hours
     * - nws_alerts – all active watches and warnings for the US and Canada
     * @param string $product
     * @return $this
     */
    public function product(string $product): self
    {
        $this->paramProduct = $product;
        return $this;
    }
    public function productObservation(): self
    {
        return $this->product(self::PRODUCT_OBSERVATION);
    }
    public function productAlerts(): self
    {
        return $this->product(self::PRODUCT_ALERTS);
    }
    public function productForecast7days(): self
    {
        return $this->product(self::PRODUCT_FORECAST_7DAYS);
    }
    public function productForecast7daysSimple(): self
    {
        return $this->product(self::PRODUCT_FORECAST_7DAYS_SIMPLE);
    }
    public function productForecastAstronomy(): self
    {
        return $this->product(self::PRODUCT_FORECAST_ASTRONOMY);
    }
    public function productForecastHourly(): self
    {
        return $this->product(self::PRODUCT_FORECAST_HOURLY);
    }
    public function productNwsAlerts(): self
    {
        return $this->product(self::PRODUCT_NWS_ALERTS);
    }

    public function __call($method, $parameters): self
    {
        echo "METHOD:" . $method;
        return $this;
    }


    public function name(string $name): self
    {
        $this->paramName = $name;
        return $this;
    }

    public function zipcode(string $zipcode): self
    {
        $this->paramZipCode = $zipcode;
        return $this;
    }

    public function latlon(float $latitude, float $longitude): self
    {
        $this->paramLatitude = $latitude;
        $this->paramLongitude = $longitude;
        return $this;
    }


    public function unitMetric(): self
    {
        $this->paramMetric = true;
        return $this;
    }
    public function unitImperial(): self
    {
        $this->paramMetric = false;
        return $this;
    }
    public function oneObservation(): self
    {
        $this->paramOneobservation = true;
        return $this;
    }
    public function moreObservation(): self
    {
        $this->paramOneobservation = false;
        return $this;
    }

    /**
     * Set Language. Language code:
     * https://developer.here.com/documentation/weather/dev_guide/topics/supported-languages.html
     * @param string $language
     * @return Weather
     */
    public function language(string $language): self
    {
        $this->paramLanguage = $language;
        return $this;
    }


    protected function queryString(): string
    {
        $retString = "";

        if ($this->paramProduct) {
            $retString = $this->addQueryParam($retString, "product", $this->paramProduct);
        }

        if ($this->paramName) {
            $retString = $this->addQueryParam($retString, "name", $this->paramName);
        }
        if ($this->paramZipCode) {
            $retString = $this->addQueryParam($retString, "zipcode", $this->paramZipCode);
        }

        if (! is_null($this->paramMetric)) {
            $retString = $this->addQueryParam($retString, "metric", $this->paramMetric ? "true" : "false");
        }

        if (!is_null($this->paramLanguage)) {
            $retString = $this->addQueryParam($retString, "language", $this->paramLanguage);
        }

        if (!is_null($this->paramOneobservation)) {
            $retString = $this->addQueryParam(
                $retString,
                "oneobservation",
                $this->paramOneobservation ? "true" : "false"
            );
        }
        if (!is_null($this->paramLatitude) && !is_null($this->paramLongitude)) {
            $retString = $this->addQueryParam($retString, "latitude", $this->paramLatitude);
            $retString = $this->addQueryParam($retString, "longitude", $this->paramLongitude);
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
        return "/weather/1.0/report." . $this->format;
    }
}
