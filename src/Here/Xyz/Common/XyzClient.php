<?php

namespace HiFolks\Milk\Here\Xyz\Common;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;
use HiFolks\Milk\Here\Common\ApiClient;
use Psr\Http\Message\ResponseInterface;

/**
 * Class XyzClient
 * @package HiFolks\Milk\Xyz\Common
 */
abstract class XyzClient extends  ApiClient
{
    /**
     * @var string
     */
    protected $spaceId;
    /**
     * @var bool
     */
    private $cacheResponse;
    /**
     * @var string|null
     */
    protected $geojsonFile;

    public const API_PATH_SPACES = "/hub/spaces";
    public const API_PATH_FEATURES = "/hub/spaces/{spaceId}/features";
    public const API_PATH_FEATURE_DETAIL = "/hub/spaces/{spaceId}/features/{featureId}";
    public const API_PATH_FEATURE_SEARCH = "/hub/spaces/{spaceId}/search";
    public const API_PATH_FEATURE_GETSPATIAL = "/hub/spaces/{spaceId}/spatial";
    public const API_PATH_FEATURE_CREATE = "/hub/spaces/{spaceId}/features";
    public const API_PATH_FEATURE_EDIT = "/hub/spaces/{spaceId}/features";
    public const API_PATH_FEATURE_DELETE = "/hub/spaces/{spaceId}/features";
    public const API_PATH_FEATURE_DELETEONE = "/hub/spaces/{spaceId}/features/{featureId}";
    public const API_PATH_FEATURE_EDITONE = "/hub/spaces/{spaceId}/features/{featureId}";
    public const API_PATH_FEATURE_CREATEEDITONE = "/hub/spaces/{spaceId}/features/{featureId}";
    public const API_PATH_STATISTICS = "/hub/spaces/{spaceId}/statistics";
    public const API_PATH_ITERATE = "/hub/spaces/{spaceId}/iterate";
    public const API_PATH_SPACEDETAIL = "/hub/spaces/{spaceId}";
    public const API_PATH_SPACEUPDATE = "/hub/spaces/{spaceId}";
    public const API_PATH_SPACEDELETE = "/hub/spaces/{spaceId}";


    protected const API_TYPE_SPACES = "SPACES";
    protected const API_TYPE_FEATURES = "FEATURES";
    protected const API_TYPE_FEATURE_DETAIL = "FEATURE_DETAIL";
    protected const API_TYPE_FEATURE_SEARCH = "FEATURE_SEARCH";
    protected const API_TYPE_FEATURE_GETSPATIAL = "FEATURE_GETSPATIAL";
    protected const API_TYPE_FEATURE_CREATE = "FEATURE_CREATE";
    protected const API_TYPE_FEATURE_EDIT = "FEATURE_EDIT";
    protected const API_TYPE_FEATURE_DELETE = "FEATURE_DELETE";
    protected const API_TYPE_FEATURE_DELETEONE = "FEATURE_DELETE_ONE";
    protected const API_TYPE_FEATURE_EDITONE = "FEATURE_EDIT_ONE";
    protected const API_TYPE_FEATURE_CREATEEDITONE = "FEATURE_CREATEEDIT_ONE";

    protected const API_TYPE_STATISTICS = "STATISTICS";
    protected const API_TYPE_ITERATE = "ITERATE";

    protected const API_TYPE_SPACEDETAIL = "SPACEDETAIL";
    protected const API_TYPE_SPACECREATE = "SPACE_CREATE";
    protected const API_TYPE_SPACEUPDATE = "SPACE_UPDATE";
    protected const API_TYPE_SPACEDELETE = "SPACE_DELETE";

    /**
     * @var string[]
     */
    protected $apiHostPaths = [
        self::API_TYPE_SPACES => self::API_PATH_SPACES,
        self::API_TYPE_FEATURES => self::API_PATH_FEATURES,
        self::API_TYPE_FEATURE_DETAIL => self::API_PATH_FEATURE_DETAIL,
        self::API_TYPE_FEATURE_SEARCH => self::API_PATH_FEATURE_SEARCH,
        self::API_TYPE_FEATURE_GETSPATIAL => self::API_PATH_FEATURE_GETSPATIAL,
        self::API_TYPE_FEATURE_CREATE => self::API_PATH_FEATURE_CREATE,
        self::API_TYPE_FEATURE_EDIT => self::API_PATH_FEATURE_EDIT,
        self::API_TYPE_FEATURE_DELETE => self::API_PATH_FEATURE_DELETE,
        self::API_TYPE_FEATURE_DELETEONE => self::API_PATH_FEATURE_DELETEONE,
        self::API_TYPE_FEATURE_EDITONE => self::API_PATH_FEATURE_EDITONE,
        self::API_TYPE_FEATURE_CREATEEDITONE => self::API_PATH_FEATURE_CREATEEDITONE,

        self::API_TYPE_STATISTICS => self::API_PATH_STATISTICS,
        self::API_TYPE_ITERATE => self::API_PATH_ITERATE,
        self::API_TYPE_SPACEDETAIL => self::API_PATH_SPACEDETAIL,
        self::API_TYPE_SPACECREATE => self::API_PATH_SPACES,
        self::API_TYPE_SPACEUPDATE => self::API_PATH_SPACEUPDATE,
        self::API_TYPE_SPACEDELETE => self::API_PATH_SPACEDELETE
    ];

    /**
     * @var string
     */
    protected $apiType;

    /**
     * Return the query string based on value setted by the user
     *
     * @return string
     */
    abstract protected function queryString(): string;

    /**
     * XyzClient constructor.
     */
    public function __construct()
    {
        $this->reset();
        parent::__construct();
    }

    /**
     * Show and list in console the current attributes of the object (spaceId, Url, content type etc)
     *
     * @return void
     */
    public function debug(): void
    {
        echo "CLIENT : " . PHP_EOL;
        echo "=========" . PHP_EOL;
        parent::debug();

        echo "SPA ID : " . $this->spaceId . PHP_EOL;
        echo "API    : " . $this->apiType . PHP_EOL;
        echo "GEOJSON: " . $this->geojsonFile . PHP_EOL;
        echo "=========" . PHP_EOL;
    }

    /**
     * Function to reset all attributes as default (Reset Object to Factory settings)
     *
     * @return void
     */
    protected function reset()
    {
        parent::reset();
        $this->apiType = self::API_TYPE_SPACES;
        $this->geojsonFile = null;
    }

    /**
     * Set the type of Endpoint developer wants to call.
     * It is used to remember the endpoint AND set the correct path of endpoint
     * The type could be one of self::API_TYPE_*
     * @param string $apiType
     * @return void
     */
    protected function setType(string $apiType)
    {
        $this->apiType = $apiType;
        $this->uri = $this->apiHostPaths[$apiType];
    }










    /**
     * Return the URL of the API, replacing the placeholder with real values.
     * For example if spaceId is 12345 the Url for Space statistics is /spaces/12345/statistics
     *
     * @return string
     */
    public function getPath(): string
    {
        $retUrl = self::API_PATH_SPACES;
        if ($this->spaceId != "") {
            $retUrl = str_replace("{spaceId}", $this->spaceId, $this->uri);
        }
        /*
        $queryParams = $this->queryString();
        if ($queryParams !== "") {
            $retUrl = $retUrl . $queryParams;
        }*/
        return $retUrl;
    }






}
