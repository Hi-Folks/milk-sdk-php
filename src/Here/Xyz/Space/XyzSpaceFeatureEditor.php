<?php

namespace HiFolks\Milk\Here\Xyz\Space;

use HiFolks\Milk\Here\Xyz\Common\XyzConfig;
use HiFolks\Milk\Here\Xyz\Common\XyzResponse;
use HiFolks\Milk\Here\Xyz\Space\XyzSpaceFeatureBase;
use HiFolks\Milk\Here\Xyz\Common\XyzClient;
use stdClass;

/**
 * Class XyzSpaceFeatureEditor
 * @package HiFolks\Milk\Xyz\Space
 */
class XyzSpaceFeatureEditor extends XyzSpaceFeatureBase
{
    /**
     * @var string[]
     */
    protected $paramAddTags = [];

    /**
     * @var string[]
     */
    protected $paramRemoveTags = [];


    /**
     * XyzSpaceFeatureEditor constructor.
     * @param XyzConfig|string|null $c
     */
    public function __construct($c = null)
    {
        parent::__construct($c);
        $this->reset();
    }

    /**
     * @param string $xyzToken
     * @return XyzSpaceFeatureEditor
     */
    public static function instance($xyzToken = ""): self
    {
        return new XyzSpaceFeatureEditor(new XyzConfig($xyzToken));
    }

    /**
     *
     */
    public function reset()
    {
        parent::reset();
        $this->paramAddTags = [];
        $this->paramRemoveTags = [];
    }

    /**
     * @param string $spaceId
     * @param mixed|null $geoJson
     * @return XyzResponse
     */
    public function create(string $spaceId, $geoJson = null)
    {
        $this->httpPut();
        $this->spaceId = $spaceId;
        $this->acceptContentType = "application/geo+json";
        $this->contentType = "application/geo+json";
        $this->setType(self::API_TYPE_FEATURE_CREATE);
        if (! is_null($geoJson)) {
            $this->requestBody = $geoJson;
        }
        return $this->getResponse();
    }


    /**
     * @param string $spaceId
     * @param string $geoJson
     * @return XyzResponse
     */
    public function edit(string $spaceId, string $geoJson)
    {
        $this->httpPost();
        $this->spaceId = $spaceId;
        $this->acceptContentType = "application/geo+json";
        $this->contentType = "application/geo+json";
        $this->setType(self::API_TYPE_FEATURE_EDIT);
        $this->requestBody = $geoJson;
        return $this->getResponse();
    }

    /**
     * @param string $spaceId
     * @param string[] $featuresIds
     * @return XyzResponse
     */
    public function delete(string $spaceId, array $featuresIds)
    {
        $this->httpDelete();
        $this->spaceId = $spaceId;
        $this->acceptContentType = "application/geo+json";
        $this->contentType = "application/geo+json";
        $this->paramFeatureIds = $featuresIds;
        $this->setType(self::API_TYPE_FEATURE_DELETE);
        $this->requestBody = null;
        return $this->getResponse();
    }


    /**
     * @param string $spaceId
     * @param string $featureId
     * @return XyzResponse
     */
    public function deleteOne(string $spaceId, string $featureId)
    {
        $this->httpDelete();
        $this->spaceId = $spaceId;
        $this->featureId = $featureId;
        $this->acceptContentType = "application/json";
        $this->contentType = "application/json";
        $this->paramFeatureIds = [];
        $this->setType(self::API_TYPE_FEATURE_DELETEONE);
        $this->requestBody = null;
        return $this->getResponse();
    }

    /**
     * Edit / patch a feature
     * https://xyz.api.here.com/hub/static/swagger/#/Edit%20Features/patchFeature
     * @param string $spaceId
     * @param string $featureId
     * @return XyzResponse
     */
    public function editOne(string $spaceId, string $featureId)
    {
        $this->httpPatch();
        $this->spaceId = $spaceId;
        $this->featureId = $featureId;
        $this->acceptContentType = "application/geo+json";
        $this->contentType = "application/geo+json";
        $this->setType(self::API_TYPE_FEATURE_EDITONE);
        return $this->getResponse();
    }


    /**
     * Create or Edit a feature
     * https://xyz.api.here.com/hub/static/swagger/#/Edit%20Features/putFeature
     * @param string $spaceId
     * @param string $featureId
     * @return XyzResponse
     */
    public function saveOne(string $spaceId, string $featureId)
    {
        $this->httpPut();
        $this->spaceId = $spaceId;
        $this->featureId = $featureId;
        $this->acceptContentType = "application/geo+json";
        $this->contentType = "application/geo+json";
        $this->setType(self::API_TYPE_FEATURE_CREATEEDITONE);
        return $this->getResponse();
    }

    /**
     * Set the tags for feature creation
     * @param string[] $tags
     * @return $this
     */
    public function addTags(array $tags): XyzSpaceFeatureEditor
    {
        $this->paramAddTags = $tags;
        return $this;
    }

    /**
     * Set the removing tags for feature editing
     * @param string[] $tags
     * @return $this
     */
    public function removeTags(array $tags): XyzSpaceFeatureEditor
    {
        $this->paramRemoveTags = $tags;
        return $this;
    }

    /**
     * @param string $body
     * @return $this
     */
    public function feature(string $body): XyzSpaceFeatureEditor
    {
        $this->requestBody = $body;
        return $this;
    }

    /**
     * @param string $file
     * @return $this
     */
    public function geojson(string $file): XyzSpaceFeatureEditor
    {
        $this->geojsonFile = $file;
        return $this;
    }


    /**
     * @return string
     */
    protected function queryString(): string
    {
        $retString = "";
        $retString = parent::queryString();

        if (is_array($this->paramAddTags) && count($this->paramAddTags) > 0) {
            $retString = $this->addQueryParam($retString, "addTags", implode(",", $this->paramAddTags));
        }

        if (is_array($this->paramRemoveTags) && count($this->paramRemoveTags) > 0) {
            $retString = $this->addQueryParam($retString, "removeTags", implode(",", $this->paramRemoveTags));
        }




        return $retString;
    }
}
