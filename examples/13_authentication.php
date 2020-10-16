<?php

require __DIR__ . "/../vendor/autoload.php";

use HiFolks\Milk\Here\RestApi\Common\RestAuth;
use HiFolks\Milk\Here\RestApi\RoutingV8;

Dotenv\Dotenv::createImmutable(__DIR__ . "/../")->load();
$keyId = $_ENV['HERE_API_KEY_ID'];
$keySecret = $_ENV['HERE_API_KEY_SECRET'];

$myToken= RestAuth::getAccessToken($keyId, $keySecret);
echo $myToken;
$routing = RoutingV8::setToken($myToken)
    ->byCar()
    ->routingModeFast()
    ->startingPoint(52.5160, 13.3779)
    ->destination(52.5185, 13.4283)
    ->returnInstructions()
    ->langIta();


$r = $routing->get();


if ($r->isError()) {
    echo "Error, " . $r->getErrorMessage();
} else {
    var_dump($r->getData());
}

