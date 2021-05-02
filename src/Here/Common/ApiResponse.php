<?php

namespace HiFolks\Milk\Here\Common;

use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class ApiResponse
{
    /**
     * @var bool
     */
    protected $isError = false;
    /**
     * @var string|null
     */
    protected $error = null;
    /**
     * @var mixed|null
     */
    protected $content = null;

    /**
     * @param ResponseInterface $response
     * @return ApiResponse
     */
    public static function createFromHttpResponse(ResponseInterface $response)
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
     * @param \Exception|GuzzleException $e
     * @return ApiResponse
     */
    public static function createFromException($e)
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
     * @return string
     */
    public function getErrorMessage(): string
    {
        return is_null($this->error) ? "" : $this->error;
    }

    /**
     * @return array<mixed>|object|null
     */
    public function getData()
    {
        return $this->content;
    }

    /**
     * @return object
     */
    public function getDataObject()
    {
        $data = $this->getData();
        if (is_object($data)) {
            return $data;
        }
        //return (object) new \stdClass();
        return (object)[];
    }

    /**
     * @return array<mixed>
     */
    public function getDataArray()
    {
        $data = $this->getData();
        if (is_array($data)) {
            return $data;
        }
        return [];
    }

    /**
     * @return false|string
     */
    public function getDataAsJsonString()
    {
        return  json_encode($this->getData());
    }
}
