<?php

namespace HiFolks\Milk\Here\Xyz\Space;

use HiFolks\Milk\Here\Xyz\Common\XyzConfig;
use HiFolks\Milk\Here\Xyz\Common\XyzClient;
use stdClass;

/**
 * Class XyzSpaceFeatureBase
 * @package HiFolks\Milk\Xyz\Space
 */
class XyzSpaceFeatureBase extends XyzClient
{
    protected string $featureId = "";
    protected array $paramFeatureIds = [];
    protected ?int $paramLimit = null;
    protected array $paramSelection = [];
    protected bool $paramSkipCache = false;
    protected string $paramHandle = "";
    private array $paramTags = [];
    private array $paramSearchParams = [];
    protected ?float $paramLatitude = null;
    protected ?float $paramLongitude = null;
    protected ?int $paramRadius = null;






    public function __construct()
    {
        $this->reset();
    }

    public static function instance($xyzToken = ""): XyzSpaceFeatureBase
    {
        $features = XyzSpaceFeatureBase::config(XyzConfig::getInstance($xyzToken));
        return $features;
    }

    public static function config(XyzConfig $c): XyzSpaceFeatureBase
    {
        $features = new XyzSpaceFeatureBase();
        $features->c = $c;
        return $features;
    }

    public static function setToken(string $token): XyzSpaceFeatureBase
    {
        $features = XyzSpaceFeatureBase::config(XyzConfig::getInstance());
        $features->c->setToken($token);
        return $features;
    }

    public function reset()
    {
        parent::reset();
        $this->acceptContentType = "application/geo+json";
        $this->contentType = "application/geo+json";
        $this->featureId = "";
        $this->spaceId = "";
        $this->paramLimit = null;
        $this->paramSelection = [];
        $this->paramSkipCache = false;
        $this->paramHandle = "";
        $this->paramFeatureIds = [];
        $this->paramLatitude = null;
        $this->paramLongitude = null;
        $this->paramRadius = null;
        $this->paramSearchParams = [];
    }

    /******************************************************
     *
     * Method for ENDPOINTS
     *
     *****************************************************/

    /******************************************************
     *
     * Method for SETTING ATTRIBUTES
     *
     *****************************************************/

    /**
    * Set the feature ids (list of ids) in the API as parameter. Useful for API where you need
    * to select multiple features
    * @param array $featureIds
    * @return $this
    */
    public function featureIds(array $featureIds): XyzSpaceFeatureBase
    {
        $this->paramFeatureIds = $featureIds;
        return $this;
    }

    /**
     * Set the feature id in the API (as PATH like /spaces/{spaceId}/features/{featureId})
     * @param string $id
     * @return $this
     */
    public function featureId(string $id): XyzSpaceFeatureBase
    {
        $this->featureId = $id;
        return $this;
    }

    /**
     * Set the limit in the API
     * @param int $limit
     * @return $this
     */
    public function limit(int $limit): XyzSpaceFeatureBase
    {
        $this->paramLimit = $limit;
        return $this;
    }

    /**
     * If set to true the response is not returned from cache. Default is false.
     * @return $this
     */
    public function skipCache(bool $skipcache = true): XyzSpaceFeatureBase
    {
        $this->paramSkipCache = $skipcache;
        return $this;
    }


    /**
     * List the properties you want to include in the response. If you have a property "title" and "description" you need to
     * use ["p.title", "p.description"].
     * @return $this
     */
    public function selection(array $propertiesList): XyzSpaceFeatureBase
    {
        $this->paramSelection = $propertiesList;
        return $this;
    }


    /**
     * Manage handle parameter for pagination.
     * @param  string $handle the hash $handle, received from the previous API Call (when you are performing
     * multiple calls for paginate results)
     * @return $this
     */
    public function handle(string $handle): XyzSpaceFeatureBase
    {
        $this->paramHandle = $handle;
        return $this;
    }

    /**
     * Set the tags for search endpoint
     * @param array $tags
     * @return $this
     */
    public function tags(array $tags): XyzSpaceFeature
    {
        $this->paramTags = $tags;
        return $this;
    }

    public function latlon($latitude, $longitude): XyzSpaceFeatureBase
    {
        $this->paramLatitude = $latitude;
        $this->paramLongitude = $longitude;
        return $this;
    }

    public function radius($radius): XyzSpaceFeatureBase
    {
        $this->paramRadius = $radius;
        return $this;
    }



    /**
     * Clean search params list
     * @param array $tags
     * @return $this
     */
    public function cleanSearchParams(): XyzSpaceFeature
    {
        $this->paramSearchParams = [];
        return $this;
    }

    /**
     * Clean search params list
     * @param array $tags
     * @return $this
     */
    public function addSearchParams($name, $value, $operator = "="): XyzSpaceFeature
    {
        $searachParam = new stdClass();
        $searachParam->name = $name;
        $searachParam->operator = $operator;
        $searachParam->value = $value;
        $this->paramSearchParams[] = $searachParam;
        return $this;
    }



    /******************************************************
     *
     * Method for  ATTRIBUTES
     *
     *****************************************************/

    protected function queryString(): string
    {
        $retString = "";

        if ($this->paramSkipCache) {
            $retString = $this->addQueryParam($retString, "skipCache", "true");
        }
        if ($this->paramLimit) {
            $retString = $this->addQueryParam($retString, "limit", $this->paramLimit);
        }
        if ($this->paramHandle) {
            $retString = $this->addQueryParam($retString, "handle", $this->paramHandle);
        }
        if (is_array($this->paramSelection) && count($this->paramSelection) > 0) {
            $retString = $this->addQueryParam($retString, "selection", implode(",", $this->paramSelection));
        }
        if (is_array($this->paramFeatureIds) && count($this->paramFeatureIds) > 0) {
            $retString = $this->addQueryParam($retString, "id", implode(",", $this->paramFeatureIds));
        }
        if (is_array($this->paramTags) && count($this->paramTags) > 0) {
            $retString = $this->addQueryParam($retString, "tags", implode(",", $this->paramTags));
        }

        if ($this->paramLatitude) {
            $retString = $this->addQueryParam($retString, "lat", $this->paramLatitude);
        }
        if ($this->paramLongitude) {
            $retString = $this->addQueryParam($retString, "lon", $this->paramLongitude);
        }
        if ($this->paramRadius) {
            $retString = $this->addQueryParam($retString, "radius", $this->paramRadius);
        }


        if (is_array($this->paramSearchParams) && count($this->paramSearchParams) > 0) {
            $tempString = "";
            //$separator = "";
            foreach ($this->paramSearchParams as $key => $searchParam) {
                //$tempString = $tempString . $separator . $searchParam->name .  $searchParam->operator . $searchParam->value;
                //$separator = ",";
                $retString = $this->addQueryParam($retString, $searchParam->name, $searchParam->value);
            }
            //$retString = $this->addQueryParam($retString, "params", $tempString);
        }

        return $retString;
    }





    /**
     * Return the URL of the API, replacing the placeholder with real values.
     * For example if spaceId is 12345 the Url for Space statistics is /spaces/12345/statistics
     *
     * @return string
     */
    public function getPath(): string
    {
        if ($this->featureId != "") {
            $this->uri = str_replace("{featureId}", $this->featureId, $this->uri);
        }

        return parent::getPath();
        //return $retUrl;
    }
}
