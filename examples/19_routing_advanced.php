<?php
require __DIR__ . "/../vendor/autoload.php";

use HiFolks\Milk\Here\RestApi\RoutingV8;
use HiFolks\Milk\Utils\Environment;

Dotenv\Dotenv::createImmutable(__DIR__ . "/../")->load();
$hereApiKey = Environment::getEnv('HERE_API_KEY');


$routingActions = RoutingV8::instance()
    ->setApiKey($hereApiKey)
    ->originAddress("Duomo, Milan, Italy")
    ->destinationAddress("Central Station, Milan, Italy")
    ->getDefaultActions();

foreach ($routingActions as $key => $action) {
    echo " - ".$action->instruction . PHP_EOL;
}


