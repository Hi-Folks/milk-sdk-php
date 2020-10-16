<?php

namespace HiFolks\Milk\Here\RestApi\Common;


use GuzzleHttp\Client;

use GuzzleHttp\Exception\ClientException;

/**
 * https://developer.here.com/documentation/authentication/dev_guide/topics/token.html
 *
 * Thanks to clear documentation:
 * https://developer.here.com/documentation/authentication/dev_guide/topics/using-aaa-javasdk-or-3rd-party-libraries.html#using-aaa-javasdk-or-3rd-party-libraries
 * https://developer.twitter.com/en/docs/basics/authentication/oauth-1-0a/creating-a-signature
 *
 */
class RestAuth
{

    /**
     * @var string[]
     */
    private static $httpBody = [
        "grant_type" => "client_credentials"
    ];
    /**
     * @var string
     */
    private static $httpMethod = "POST";
    /**
     * @var string
     */
    private static $httpUrl = 'https://account.api.here.com/oauth2/token';

    /**
     * @var string
     */
    private static $oauthNonce;
    /**
     * @var int
     */
    private static $oauthTimestamp;
    /**
     * @var string
     */
    private static $oauthSignatureMethod;
    /**
     * @var string
     */
    private static $oauthVersion;

    private static function initOauth()
    {
        self::$oauthNonce = mt_rand();
        self::$oauthTimestamp = time();
        self::$oauthSignatureMethod = "HMAC-SHA256";
        self::$oauthVersion = "1.0";
    }

    public static function createSignature($accessKeyId, $accessKeySecret)
    {
        self::initOauth();

        $oauth1Param = [
            'oauth_consumer_key' => $accessKeyId,
            'oauth_signature_method' => self::$oauthSignatureMethod,
            'oauth_timestamp' => self::$oauthTimestamp,
            'oauth_nonce' => self::$oauthNonce,
            'oauth_version' => self::$oauthVersion
        ];

        $paramString =
            "grant_type=client_credentials&" .
            "oauth_consumer_key=" . urlencode($oauth1Param['oauth_consumer_key']) .
            "&oauth_nonce=" . urlencode($oauth1Param['oauth_nonce']) .
            "&oauth_signature_method=" . urlencode($oauth1Param['oauth_signature_method']) .
            "&oauth_timestamp=" . urlencode($oauth1Param['oauth_timestamp']) .
            //        "&oauth_token=".
            "&oauth_version=" . urlencode($oauth1Param['oauth_version']);

        $baseString = self::$httpMethod . "&"
            . urlencode(self::$httpUrl) . "&"
            . urlencode($paramString);
        $signingKey = urlencode($accessKeySecret) . "&";
        $signature = urlencode(
            base64_encode(
                hash_hmac(
                    'sha256',
                    $baseString,
                    $signingKey,
                    true
                )
            )
        );
        $oauth1Param['oauth_signature']  =  $signature;
        $headerOauth = "OAuth ";
        $sep = "";
        foreach ($oauth1Param as $key => $value) {
            $headerOauth = $headerOauth . $sep . $key . "=\"" . $value . "\"";
            $sep = ",";
        }
        $client = new Client();
        try {
            $res = $client->request(self::$httpMethod, self::$httpUrl, [
                'form_params' => self::$httpBody,
                'headers'  => [
                    "Authorization" => $headerOauth
                ]
            ]);
            $res->getStatusCode();
            return $res->getBody();
        } catch (ClientException $e) {
            //echo Psr7\str($e->getRequest());
            //echo Psr7\str($e->getResponse());
            return "";
        }
    }


    public static function getAccessToken($accessKeyId, $accessKeySecret)
    {
        $stringJson = self::createSignature($accessKeyId, $accessKeySecret);
        $myToken = json_decode($stringJson)->access_token;
        return $myToken;
    }
}
