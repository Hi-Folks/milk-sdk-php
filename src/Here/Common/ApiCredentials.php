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

    /**
     * @var string
     */
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


    /**
     * @param string $appId
     * @param string $appCode
     * @return void
     */
    public function setAppIdAppCode(string $appId, string $appCode): void
    {
        $this->appId = $appId;
        $this->appCode = $appCode;
        $this->credentialTypeSelected = self::CREDENTIAL_TYPE_APPCODE;
    }

    /**
     * @return string
     */
    public function getAppId(): string
    {
        return $this->appId;
    }

    /**
     * @return string
     */
    public function getAppCode(): string
    {
        return $this->appCode;
    }


    /**
     * @param array<string,string> $state
     * @return self
     */
    public static function __set_state(array $state): self
    {
        return new self(
            $state['token']
        );
    }

    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * @param string $accessToken
     * @return void
     */
    public function setAccessToken(string $accessToken): void
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
     * @return array<string,string>
     */
    public function toArray(): array
    {
        return [
            'accessToken' => $this->accessToken,
            'appId' => $this->appId,
            'appCode' => $this->appCode,
            'apiKey' =>  $this->apiKey
        ];
    }

    /**
     * @return string
     */
    public function serialize(): string
    {
        return json_encode($this->toArray());
    }

    /**
     * @param string $serialized
     * @return void
     */
    public function unserialize(string $serialized): void
    {
        $data = json_decode($serialized, true);
        $this->accessToken = $data['accessToken'];
        $this->appId = $data['appId'];
        $this->appCode = $data['appCode'];
        $this->apiKey = $data['apiKey'];
    }

    /**
     * @return bool
     */
    public function isBearer(): bool
    {

        return $this->credentialTypeSelected === self::CREDENTIAL_TYPE_OAUTH2TOKEN;
    }

    /**
     * @return string
     */
    public function getHeaderAuthorization(): string
    {
        return "Bearer {$this->getAccessToken()}";
    }
}
