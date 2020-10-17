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

    public function __construct()
    {
        parent::__construct();
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
