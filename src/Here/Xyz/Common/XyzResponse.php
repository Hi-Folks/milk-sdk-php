<?php

namespace HiFolks\Milk\Here\Xyz\Common;

use HiFolks\Milk\Here\Common\ApiResponse;

class XyzResponse extends ApiResponse
{


    public function __construct(ApiResponse $ar)
    {
        $this->isError = $ar->isError();
        $this->content = $ar->content;
        $this->error = $ar->error;
    }
}
