<?php

namespace HiFolks\Milk\Here\Xyz\Space;

use HiFolks\Milk\Here\Xyz\Common\XyzConfig;
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
     * @var array
     */
    protected $paramAddTags = [];

    /**
     * @var array
     */
    protected $paramRemoveTags = [];


    public function __construct($c = null)
    {
        parent::__construct($c);
        $this->reset();
    }

    public static function instance($xyzToken = ""): self
    {
        return new XyzSpaceFeatureEditor(new XyzConfig($xyzToken));
    }

    public function reset()
    {
        parent::reset();
        $this->paramAddTags = [];
        $this->paramRemoveTags = [];
    }

    public function create($spaceId, $geojson = null)
    {
        $this->httpPut();
        $this->spaceId = $spaceId;
        $this->acceptContentType = "application/geo+json";
        $this->contentType = "application/geo+json";
        $this->setType(self::API_TYPE_FEATURE_CREATE);
        if (! is_null($geojson)) {
            $this->requestBody = $geojson;
        }
        return $this->getResponse();
    }


    public function edit($spaceId, $geojson)
    {
        $this->httpPost();
        $this->spaceId = $spaceId;
        $this->acceptContentType = "application/geo+json";
        $this->contentType = "application/geo+json";
        $this->setType(self::API_TYPE_FEATURE_EDIT);
        $this->requestBody = $geojson;
        return $this->getResponse();
    }

    public function delete($spaceId, array $featuresIds)
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


    public function deleteOne($spaceId, $featureId)
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
     */
    public function editOne($spaceId, $featureId)
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
     */
    public function saveOne($spaceId, $featureId)
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
     * @param array $tags
     * @return $this
     */
    public function addTags(array $tags): XyzSpaceFeatureEditor
    {
        $this->paramAddTags = $tags;
        return $this;
    }

    /**
     * Set the removing tags for feature editing
     * @param array $tags
     * @return $this
     */
    public function removeTags(array $tags): XyzSpaceFeatureEditor
    {
        $this->paramRemoveTags = $tags;
        return $this;
    }

    public function feature(string $body): XyzSpaceFeatureEditor
    {
        $this->requestBody = $body;
        return $this;
    }

    public function geojson(string $file): XyzSpaceFeatureEditor
    {
        $this->geojsonFile = $file;
        return $this;
    }


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
