<?php

namespace HiFolks\Milk\Here\Common;

/**
 * Class ApiCredentials
 * @package HiFolks\Milk\Here\Common
 * The supported authentication credential types are:
 * - API Key Credentials
 * - OAuth 2.0 Token Credentials
 * - APP CODE Credentials
 */
class ApiCredentials
{
    public const CREDENTIAL_TYPE_API_KEY = "APIKEY";
    public const CREDENTIAL_TYPE_OAUTH2TOKEN = "OAUTH2TOKEN";
    public const CREDENTIAL_TYPE_APPCODE = "APPCODE";

    private $credentialTypeSelected = self::CREDENTIAL_TYPE_API_KEY;
    /**
     * @var string
     * https://developer.here.com/documentation/authentication/dev_guide/topics/token.html
     * Access token to send as Bearer token in headers
     * Authorization: Bearer <token>
     */
    private $accessToken;


    /**
     * @var string
     * https://developer.here.com/documentation/authentication/dev_guide/topics/api-key-credentials.html
     * API Key needs to be added to query string as parameter
     * apiKey=
     */
    private $apiKey;

    /**
     * App ID for App code credentials
     * https://developer.here.com/documentation/authentication/dev_guide/topics/app-credentials.html
     * @var string
     */
    private $appId;
    /**
     * @var string
     */
    private $appCode;


    /**
     * Constructs a new ApiCredentials object, with the specified
     * access token
     *
     * @param string $accessToken   Security token to use
     */
    public function __construct($accessToken = "")
    {
        $this->accessToken = $accessToken;

        $this->appId = "";
        $this->appCode = "";

        $this->apiKey = "";

        $this->credentialTypeSelected = self::CREDENTIAL_TYPE_OAUTH2TOKEN;
    }


    public function setAppIdAppCode($appId, $appCode)
    {
        $this->appId = $appId;
        $this->appCode = $appCode;
        $this->credentialTypeSelected = self::CREDENTIAL_TYPE_APPCODE;
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function getAppCode()
    {
        return $this->appCode;
    }



    public static function __set_state(array $state)
    {
        return new self(
            $state['token']
        );
    }
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    public function setAccessToken(string $accessToken)
    {
        $this->accessToken = $accessToken;
        $this->credentialTypeSelected = self::CREDENTIAL_TYPE_OAUTH2TOKEN;
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     */
    public function setApiKey(string $apiKey): void
    {
        $this->apiKey = $apiKey;
        $this->credentialTypeSelected = self::CREDENTIAL_TYPE_API_KEY;
    }

    /**
     * Return the Array representation of this object
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'accessToken'     => $this->accessToken,
            'appId' => $this->appId,
            'appCode' => $this->appCode,
            'apiKey' =>  $this->apiKey
        ];
    }

    public function serialize()
    {
        return json_encode($this->toArray());
    }

    public function unserialize($serialized)
    {
        $data = json_decode($serialized, true);
        $this->accessToken = $data['accessToken'];
        $this->appId = $data['appId'];
        $this->appCode = $data['appCode'];
        $this->apiKey = $data['apiKey'];
    }

    public function isBearer()
    {

        return $this->credentialTypeSelected === self::CREDENTIAL_TYPE_OAUTH2TOKEN;
    }

    public function getHeaderAuthorization()
    {
        return "Bearer {$this->getAccessToken()}";
    }
}
