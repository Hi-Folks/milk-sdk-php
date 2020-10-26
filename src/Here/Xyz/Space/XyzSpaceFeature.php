<?php

namespace HiFolks\Milk\Here\Xyz\Space;

use HiFolks\Milk\Here\Xyz\Common\XyzConfig;

/**
 * Class XyzSpaceFeature
 * @package HiFolks\Milk\Here\Xyz\Space
 */
class XyzSpaceFeature extends XyzSpaceFeatureBase
{
    /**
     * XyzSpaceFeature constructor.
     * @param XyzConfig|string|null $c
     */
    public function __construct($c = null)
    {
        parent::__construct($c);
        $this->reset();
    }

    /**
     * @param string $xyzToken
     * @return XyzSpaceFeature
     */
    public static function instance($xyzToken = ""): self
    {
        return new XyzSpaceFeature(new XyzConfig($xyzToken));
    }


    /**
     *
     */
    public function reset()
    {
        parent::reset();
    }

    /**
     * @param string $spaceId
     * @return XyzSpaceFeature
     */
    public function iterate(string $spaceId): XyzSpaceFeature
    {
        $this->spaceId = $spaceId;
        $this->acceptContentType = "application/geo+json";
        $this->setType(self::API_TYPE_ITERATE);
        return $this;
    }

    /**
     * @param string $spaceId
     * @return XyzSpaceFeature
     */
    public function features(string $spaceId): XyzSpaceFeature
    {
        $this->spaceId = $spaceId;
        $this->acceptContentType = "application/geo+json";
        $this->setType(self::API_TYPE_FEATURES);
        return $this;
    }

    /**
     * @param string $spaceId
     * @return $this
     */
    public function search(string $spaceId): XyzSpaceFeature
    {
        $this->spaceId = $spaceId;
        $this->httpGet();
        $this->acceptContentType = "application/geo+json";
        $this->setType(self::API_TYPE_FEATURE_SEARCH);

        return $this;
    }

    /**
     * @param string $spaceId
     * @param float|null $latitude
     * @param float|null $longitude
     * @param int|null $radius
     * @return $this
     */
    public function spatial(string $spaceId, $latitude = null, $longitude = null, $radius = null): XyzSpaceFeature
    {
        $this->spaceId = $spaceId;
        if (! is_null($latitude)) {
            $this->paramLatitude = $latitude;
        }
        if (!is_null($longitude)) {
            $this->paramLongitude = $longitude;
        }
        if (!is_null($radius)) {
            $this->paramRadius = $radius;
        }

        $this->httpGet();
        $this->acceptContentType = "application/geo+json";
        $this->setType(self::API_TYPE_FEATURE_GETSPATIAL);

        return $this;
    }

    /**
     * @param string $featureId
     * @param string $spaceId
     * @return $this
     */
    public function feature(string $featureId, $spaceId = ""): XyzSpaceFeature
    {
        if ($spaceId !== "") {
            $this->spaceId = $spaceId;
        }
        $this->featureId = $featureId;
        $this->acceptContentType = "application/geo+json";
        $this->setType(self::API_TYPE_FEATURE_DETAIL);
        return $this;
    }
}
