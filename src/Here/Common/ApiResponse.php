<?php

namespace HiFolks\Milk\Here\Common;

use GuzzleHttp\Psr7\Response;

class ApiResponse
{
    /**
     * @var bool
     */
    private $isError = false;
    /**
     * @var null
     */
    private $error = null;
    /**
     * @var null
     */
    private $content = null;

    /**
     * @param Response $response
     * @return ApiResponse
     */
    public static function createFromHttpResponse(Response $response)
    {
        $r =  new self();

        $r->isError = ! (
            ( $response->getStatusCode() >= 200 )
            &&
            ( $response->getStatusCode() < 300 )
        );
        $r->content = json_decode($response->getBody()->getContents());
        if ($r->isError()) {
            $r->error = $r->content->errorMessage;
        }
        return $r;
    }

    /**
     * @param \Exception $e
     * @return ApiResponse
     */
    public static function createFromException(\Exception $e)
    {
        $r =  new self();
        $r->isError = true;
        $r->error = $e->getMessage();
        $r->content = ["error" => $e->getMessage()];
        return $r;
    }


    /**
     * @return bool
     */
    public function isError()
    {
        return $this->isError;
    }

    /**
     * @return null
     */
    public function getErrorMessage()
    {
        return $this->error;
    }

    /**
     * @return array|object|null
     */
    public function getData()
    {
        return $this->content;
    }

    /**
     * @return false|string
     */
    public function getDataAsJsonString()
    {
        return  json_encode($this->getData());
    }

}
