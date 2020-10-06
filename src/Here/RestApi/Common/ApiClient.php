<?php

namespace HiFolks\Milk\Here\RestApi\Common;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

abstract class ApiClient
{
    /**
     * @var ApiConfig
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
     * for example: /weather/1.0/report.json
     */
    abstract protected function getPath(): string;

    /**
     * Return the query string based on value setted by the user
     *
     * @return string
     */
    abstract protected function queryString(): string;

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
        echo "PATH   : " . $this->getPath() . PHP_EOL;
        echo "METHOD : " . $this->method . PHP_EOL;
        echo "ACCEPT : " . $this->acceptContentType . PHP_EOL;
        echo "C TYPE : " . $this->contentType . PHP_EOL;
        echo "TOKEN  : " . $this->c->getCredentials()->getAccessToken() . PHP_EOL;
        echo "APP_ID : " . $this->c->getCredentials()->getAppId() . PHP_EOL;
        echo "APP_COD: " . $this->c->getCredentials()->getAppCode() . PHP_EOL;
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
        $this->cacheResponse = false;
        $this->requestBody = null;
        $this->geojsonFile = null;
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
     * @return ApiClient
     */
    public function cacheResponse(bool $wannaCache): ApiClient
    {
        $this->cacheResponse = $wannaCache;
        return $this;
    }

    /**
     * @param string $url , the URL (or the path) to add the new $name parameter with the value $value
     * @param string $name
     * @param $value
     * @param bool $encodeValue
     * @return string
     */
    protected function addQueryParam(string $url, string $name, $value, $encodeValue = true): string
    {
        $value = $encodeValue ? urlencode($value) : $value;
        $url .= (parse_url($url, PHP_URL_QUERY) ? '&' : '?') . urlencode($name) . '=' . $value;
        return $url;
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface|null
     */
    public function getResponse()
    {
        $res = false;
        try {
            $res = $this->call($this->getUrl(), $this->acceptContentType, $this->method, $this->requestBody, $this->contentType);
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $res = $e->getResponse();
            } else {
                $res = (object) [
                    "message" => $e->getMessage(),
                    "code" => $e->getCode()
                ];
            }
        } catch (Exception $e) {
            //echo $res->getStatusCode();
        }
        return $res;
    }

    /**
     * @return mixed
     */
    public function getJson(): string
    {
        //echo $this->getUrl() . PHP_EOL;
        //echo "---".$this->getResponse()->getStatusCode()."---";

        if ($this->cacheResponse) {
            $cache_tag = md5($this->getUrl() . $this->acceptContentType . $this->method);
            $file_cache = "./cache/" . $cache_tag;
            if (file_exists($file_cache)) {
                $content = file_get_contents($file_cache);
            } else {
                $content = $this->getResponse()->getBody();
                echo $this->getResponse()->getStatusCode();
                if ($this->getResponse()->getStatusCode() == 200) {
                    file_put_contents($file_cache, $content);
                }
            }
        } else {
            $content = $this->getResponse()->getBody();
        }
        return $content;
    }
    public function get()
    {
        return json_decode($this->getJson());
    }


    /**
     * Return the URL of the API, replacing the placeholder with real values.
     * For example if spaceId is 12345 the Url for Space statistics is /spaces/12345/statistics
     *
     * @return string
     */
    public function getPathQuery(): string
    {
        $retUrl = $this->getPath();
        $queryParams = $this->queryString();
        if ($queryParams !== "") {
            $retUrl = $retUrl . $queryParams;
        }
        return $retUrl;
    }

    public function getUrl(): string
    {
        return $this->c->getHostname() . $this->getPathQuery();
    }


    public function call($uri, $acceptContentType = 'application/json', $method, $body = null, $contentType = "application/json")
    {
        $client = new Client();

        $headers = [
            'User-Agent' => 'milk-sdk-php/0.1.0'
        ];
        if ($acceptContentType !== "") {
            $headers['Accept'] = $acceptContentType;
        }
        if ($this->c->getCredentials()->getAccessToken() != "") {
            $headers['Authorization'] = "Bearer {$this->c->getCredentials()->getAccessToken()}";
        }
        if (in_array($method, ["POST", "PATCH", "PUT", "DELETE"])) {
            if ($contentType !== "") {
                $headers['Content-Type'] = $contentType;
            }
        }


        $requestOptions = [
            //'debug' => true,
            'headers' => $headers
        ];
        if (!is_null($body)) {
            $requestOptions["body"] = $body;
        } else {
            if (!is_null($this->geojsonFile)) {
                $requestOptions["body"] = file_get_contents($this->geojsonFile);
            }
        }

        $res = $client->request($method, $this->getUrl(), $requestOptions);
        //echo $res->getStatusCode();
        //echo $res->getBody();
        return $res;
    }



    protected function getConfig(): ApiConfig
    {
        return $this->c;
    }
}
