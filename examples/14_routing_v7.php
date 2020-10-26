<?php
require __DIR__ . "/../vendor/autoload.php";

use HiFolks\Milk\Here\RestApi\RoutingV7;

Dotenv\Dotenv::createImmutable(__DIR__ . "/../")->load();
$hereApiKey = $_ENV['HERE_API_KEY'];

$r = (new RoutingV7())
    ->setApiKey($hereApiKey)
    ->byFoot()
    ->typeFastest()
    ->startingPoint(52.5160, 13.3779)
    ->destination(52.5185, 13.4283)
    ->get();
var_dump($r->getData());
var_dump($r->isError());
var_dump($r->getErrorMessage());
var_dump($r->getDataAsJsonString());

$r = (new RoutingV7())
    ->setApiKey($hereApiKey)
    ->byFoot()
    ->typeFastest()
    ->startingPoint(52.5160, 13.3779)
    ->destination(52.5185, 13.4283)
    ->getManeuverInstructions();

var_dump($r);
