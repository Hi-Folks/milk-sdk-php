<?php

namespace HiFolks\Milk\Here\RestApi\Common;

use HiFolks\Milk\Here\Common\ApiClient;

abstract class RestClient extends ApiClient
{


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

    abstract protected function getHostname(): string;

    /**
     * XyzClient constructor.
     * @param RestConfig|string|null $c
     */
    public function __construct($c = null)
    {
        $this->reset();
        if (! is_null($c)) {
            if (is_object($c)) {
                $c->setHostname($this->getHostname());
                $this->c = $c;
            } else {
                $this->c = RestConfig::getInstance($c, $this->getHostname());
            }
        } else {
            $this->c = RestConfig::getInstance("", $this->getHostname());
        }

        parent::__construct();
    }

    public function setConfig(RestConfig $c): self
    {
        $this->c = $c;
        return $this;
    }

    public function setToken(string $token): self
    {
        if (is_null($this->c)) {
            $this->c = new RestConfig($token);
        } else {
            $this->c->setToken($token);
        }
        return $this;
    }

    public function setApiKey(string $apiKey): self
    {
        if (is_null($this->c)) {
            $this->c = new RestConfig();
            $this->c->setApiKey($apiKey);
        } else {
            $this->c->setApiKey($apiKey);
        }
        return $this;
    }

    public function setAppIdAppCode(string $appId, string $appCode): self
    {
        if (is_null($this->c)) {
            $this->c = new RestConfig();
            $this->c->setAppIdAppCode($appId, $appCode);
        } else {
            $this->c->setAppIdAppCode($appId, $appCode);
        }
        return $this;
    }

    public function makeCredentialQueryParams(string $retString): string
    {
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
    }
}
