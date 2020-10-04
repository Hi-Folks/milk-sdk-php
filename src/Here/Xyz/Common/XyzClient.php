<?php

namespace HiFolks\Milk\Here\Xyz\Common;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;

/**
 * Class XyzClient
 * @package HiFolks\Milk\Xyz\Common
 */
abstract class XyzClient
{
    /**
     * @var XyzConfig
     */
    protected $c;
    /**
     * @var string
     */
    protected $uri;
    /**
     * @var string
     */
    protected $acceptContentType;
    /**
     * @var string
     */
    protected $contentType;
    /**
     * @var
     */
    protected $requestBody;
    /**
     * @var string
     */
    protected $spaceId;
    /**
     * @var string
     */
    private $method;
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
    protected string $apiType;

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
        echo "URL    : " . $this->getUrl() . PHP_EOL;
        echo "METHOD : " . $this->method . PHP_EOL;
        echo "SPA ID : " . $this->spaceId . PHP_EOL;
        echo "ACCEPT : " . $this->acceptContentType . PHP_EOL;
        echo "C TYPE : " . $this->contentType . PHP_EOL;
        echo "API    : " . $this->apiType . PHP_EOL;
        echo "TOKEN  : " . $this->c->getCredentials()->getAccessToken() . PHP_EOL;
        echo "GEOJSON: " . $this->geojsonFile . PHP_EOL;
        var_dump($this->requestBody);
        echo "=========" . PHP_EOL;
    }

    /**
     * Function to reset all attributes as default (Reset Object to Factory settings)
     *
     * @return void
     */
    protected function reset()
    {
        $this->uri = "";
        $this->contentType = "application/json";
        $this->acceptContentType = "application/json";
        $this->method = "GET";
        $this->apiType = self::API_TYPE_SPACES;
        $this->cacheResponse = false;
        $this->requestBody = null;
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
     * Set the HTTP Method for the endpoint ("GET", "POST", "DELETE", "PATCH", "PUT")
     * @param string $method
     * @return $this
     */
    public function method(string $method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * Set the GET method
     * @return $this
     */
    public function httpGet()
    {
        return $this->method("GET");
    }

    /**
     * Set the POST method
     * @return $this
     */
    public function httpPost()
    {
        return $this->method("POST");
    }

    /**
     * Set the PUT method
     * @return $this
     */
    public function httpPut()
    {
        return $this->method("PUT");
    }

    /**
     * Set the PATCH method
     * @return $this
     */
    public function httpPatch()
    {
        return $this->method("PATCH");
    }

    /**
     * Set the DELETE method
     * @return $this
     */
    public function httpDelete()
    {
        return $this->method("DELETE");
    }

    /**
     * Define if switch on or of caching response from APIs
     *
     * @param boolean $wannaCache
     * @return XyzClient
     */
    public function cacheResponse(bool $wannaCache = true): XyzClient
    {
        $this->cacheResponse = $wannaCache;
        return $this;
    }

    /**
     * @param $url, the URL (or the path) to add the parameter
     * @param $name, the name of the parameter
     * @param $value, the value of the parameter
     * @return string
     */
    protected function addQueryParam(string $url, string $name, $value): string
    {
        $url .= (parse_url($url, PHP_URL_QUERY) ? '&' : '?') . urlencode($name) . '=' . urlencode($value);
        return $url;
    }

    /**
     * @return XyzResponse|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getResponse()
    {
        try {
            $res = $this->call(
                $this->getUrl(),
                $this->acceptContentType,
                $this->method,
                $this->requestBody,
                $this->contentType
            );
            $response = XyzResponse::createFromHttpResponse($res);
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                //$res = $e->getResponse();
                $response = XyzResponse::createFromException($e);
            } else {
                $response = XyzResponse::createFromException($e);
                /**
                $res = new Response(500, [], (object) [
                    "message" => $e->getMessage(),
                    "code" => $e->getCode()
                ]);
                 */
            }
        } catch (Exception $e) {
            $response = XyzResponse::createFromException($e);

        }
        return $response;
    }

    /**
     * @return mixed
     */
    public function get()
    {
        //echo $this->getUrl() . PHP_EOL;
        if ($this->cacheResponse) {
            $cache_tag = md5($this->getUrl() . $this->acceptContentType . $this->method);
            $file_cache = "./cache/" . $cache_tag;
            if (file_exists($file_cache)) {
                $response = unserialize(file_get_contents($file_cache));
            } else {
                $response = $this->getResponse();
                $cachedContent = serialize($response);
                file_put_contents($file_cache, $cachedContent);
            }
        } else {
            try {
                //$response = XyzResponse::createFromHttpResponse($this->getResponse());
                $response = $this->getResponse();
                //$response->getAsArray();
            } catch (Exception $e) {
                $response = XyzResponse::createFromException($e);
            }
        }
        return $response;
        //return is_array($content) ? $content : json_decode($content);
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
        $queryParams = $this->queryString();
        if ($queryParams !== "") {
            $retUrl = $retUrl . $queryParams;
        }
        return $retUrl;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->c->getHostname() . $this->getPath();
    }


    /**
     * @param $uri
     * @param string $acceptContentType
     * @param $method
     * @param null $body
     * @param string $contentType
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function call($uri, $acceptContentType = 'application/json', $method = 'GET', $body = null, $contentType = "application/json")
    {
        $client = new Client();

        $headers = [
            'User-Agent' => 'milk-sdk-php/0.1.0',
            'Accept'     => $acceptContentType,
        ];
        if ($this->c->getCredentials()->getAccessToken() != "") {
            $headers['Authorization'] = "Bearer {$this->c->getCredentials()->getAccessToken()}";
        }
        if (in_array($method, ["POST", "PATCH", "PUT", "DELETE"])) {
            $headers['Content-Type'] = $contentType;
        }


        $requestOptions = [
            //'debug' => true,
            'headers' => $headers
        ];
        if (! is_null($body)) {
            $requestOptions["body"] = $body;
        } else {
            if (! is_null($this->geojsonFile)) {
                $requestOptions["body"] = file_get_contents($this->geojsonFile);
            }
        }

        $res = $client->request($method, $this->getUrl(), $requestOptions);
        //echo $res->getStatusCode();
        //echo $res->getBody();
        return $res;
    }


    /**
     * @return XyzConfig
     */
    protected function getConfig(): XyzConfig
    {
        return $this->c;
    }
}
