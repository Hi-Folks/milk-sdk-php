<?php

namespace HiFolks\Milk\Here\Xyz\Common;

use GuzzleHttp\Exception\GuzzleException;
use HiFolks\Milk\Here\Common\ApiResponse;

class XyzResponse extends ApiResponse
{


    public function __construct(ApiResponse $ar)
    {
        $this->isError = $ar->isError();
        $this->content = $ar->content;
        $this->error = $ar->error;
    }

    /**
     * @param \Exception|GuzzleException $e
     * @return XyzResponse
     */
    public static function createFromException($e)
    {
        return new self(ApiResponse::createFromException($e));
    }
}
