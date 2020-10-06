<?php

namespace HiFolks\Milk\Here\RestApi\Common;

class ApiCredentials
{
    /**
     * @var string
     */
    private $token;



    /**
     * App ID for App code credentials
     * https://developer.here.com/documentation/authentication/dev_guide/topics/app-credentials.html
     * @var string
     */
    private $appId;
    /**
     * @var string
     */
    private $appToken;


    /**
     * Constructs a new ApiCredentials object, with the specified
     * access token
     *
     * @param string $token   Security token to use
     */
    public function __construct($token = "")
    {
        $this->token = $token;

        $this->appId = "";
        $this->appCode = "";
    }


    public function setAppIdAppCode($appId, $appCode) {
        $this->appId = $appId;
        $this->appCode = $appCode;
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function getAppCode()
    {
        return $this->appCode;
    }

    public static function token($token): ApiCredentials
    {
        return new ApiCredentials($token);
    }



    public static function __set_state(array $state)
    {
        return new self(
            $state['token']
        );
    }
    public function getAccessToken()
    {
        return $this->token;
    }

    public function setAccessToken(string $token)
    {
        $this->token = $token;
    }

    /**
     * Return the Array representation of this object
     * [
     *   'token' => 'some token'
     * ]
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'token'     => $this->token
        ];
    }

    public function serialize()
    {
        return json_encode($this->toArray());
    }

    public function unserialize($serialized)
    {
        $data = json_decode($serialized, true);
        $this->token = $data['token'];
    }
}
