<?php

namespace HiFolks\Milk\Here\RestApi;

use HiFolks\Milk\Here\Common\LatLong;
use HiFolks\Milk\Here\RestApi\Common\RestClient;
use HiFolks\Milk\Here\RestApi\Common\RestConfig;

/**
 * Class MapImage
 * @package HiFolks\Milk\Here\RestApi
 */
class MapImage extends RestClient
{

    private const BASE_URL = "https://image.maps.ls.hereapi.com";

    private ?LatLong $paramCenter;
    private ?string $paramCenterAddress;
    private ?int $paramZoom;
    /**
     * @var array<mixed>|null
     */
    private ?array $paramPois;
    private ?int $paramWidth;
    private ?int $paramHeight;
    private ?bool $enableGeocoding;



    private const DEFAULT_ZOOM = 16;
    private const DEFAULT_LATITUDE = 41.890243;
    private const DEFAULT_LONGITUDE = 12.492356;


    /**
     * MapImage constructor.
     * @param RestConfig|string|null $c
     */
    public function __construct($c = null)
    {
        parent::__construct($c);
        $this->reset();
    }

    /**
     * @param string $apiToken
     * @return MapImage
     */
    public static function instance($apiToken = ""): self
    {
        $rc = new RestConfig();
        $rc->setApiKey($apiToken);
        return new MapImage($rc);
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
        //$this->paramCenter = new LatLong(self::DEFAULT_LATITUDE, self::DEFAULT_LONGITUDE);
        $this->paramCenter = null;
        $this->paramZoom = self::DEFAULT_ZOOM;
        $this->enableGeocoding = false;
        $this->paramCenterAddress = "";
        $this->paramPois = [];
        $this->paramWidth = 0;
        $this->paramHeight = 0;
    }

    /**
     * @param int $zoom
     * @return self
     */
    public function zoom(int $zoom): self
    {
        $this->paramZoom = $zoom;
        return $this;
    }
    public function center(float $lat, float $long): self
    {
        $this->paramCenter = new LatLong($lat, $long);
        return $this;
    }
    public function centerAddress(string $address): self
    {
        $this->enableGeocoding = true;
        $this->paramCenterAddress = $address;
        return $this;
    }
    public function width(int $width): self
    {
        $this->paramWidth = $width;
        return $this;
    }
    public function height(int $height): self
    {
        $this->paramHeight = $height;
        return $this;
    }
    public function addPoi(
        float $lat,
        float $lng,
        string $fillColor = "",
        string $textColor = "",
        string $textSize = "",
        string $customText = ""
    ): self {
        $this->paramPois[] = [
            "lat" => $lat,
            "lng" => $lng,
            "fillColor" => $fillColor,
            "textColor" => $textColor,
            "textSize" => $textSize,
            "customText" => $customText
        ];
        return $this;
    }

    public function enableGeocoding(bool $enable = true): self
    {
        $this->enableGeocoding = $enable;
        return $this;
    }



    protected function queryString(): string
    {
        $this->resolveGeocoding();
        $retString = "";

        if ($this->paramCenter) {
            $retString = $this->addQueryParam($retString, "c", $this->paramCenter->getString(), false);
        }
        if ($this->paramWidth > 0) {
            $retString = $this->addQueryParam($retString, "w", $this->paramWidth, false);
        }
        if ($this->paramHeight > 0) {
            $retString = $this->addQueryParam($retString, "h", $this->paramHeight, false);
        }
        if ($this->paramZoom) {
            $retString = $this->addQueryParam($retString, "z", $this->paramZoom, false);
        }
        for ($i = 0; $i < count($this->paramPois); $i++) {
            $item = $this->paramPois[$i];
            $retStringValue = "";
            $retStringValue = $item["lat"] . "," . $item["lng"];
            if (
                $item["fillColor"] !== "" ||
                $item["textColor"] !== "" ||
                $item["textSize"] !== "" ||
                $item["customText"] !== ""
            ) {
                $retStringValue = $retStringValue . ";" .
                    $item["fillColor"] . ";" .
                    $item["textColor"] . ";" .
                    $item["textSize"] . ";" .
                    $item["customText"] . ";";
            }
            $retString = $this->addQueryParam(
                $retString,
                "poix" . strval($i),
                $retStringValue
            );
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


    public function resolveGeocoding(): void
    {

        if (! $this->enableGeocoding) {
            // maybe forcing enableGeocoding
            $this->enableGeocoding =
                (is_null($this->paramCenter) && $this->paramCenterAddress !== "");
        }

        if ($this->enableGeocoding) {
            if ($this->paramCenterAddress !== "") {
                $g = Geocode::instance($this->getConfig()->getCredentials()->getApiKey())
                    ->q($this->paramCenterAddress)
                    ->get();
                if (! $g->isError()) {
                    $place = $g->getData()->items[0];
                    $this->paramCenter = new LatLong($place->position->lat, $place->position->lng);
                }
            }
        }
    }

    public function get()
    {
        return parent::get();
    }


    protected function getPath(): string
    {
        return "/mia/1.6/mapview";
    }
}
