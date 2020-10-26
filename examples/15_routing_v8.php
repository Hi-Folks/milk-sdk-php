<?php
require __DIR__ . "/../vendor/autoload.php";

use HiFolks\Milk\Here\RestApi\RoutingV8;

Dotenv\Dotenv::createImmutable(__DIR__ . "/../")->load();
$hereApiKey = $_ENV['HERE_API_KEY'];

function print_row($item, $key)
{
    echo $key + 1 . " " . $item->id . " " . $item->owner . " " . $item->title . "\n";
}

$routingActions = RoutingV8::instance()
    ->setApiKey($hereApiKey)
    ->byCar()
    ->routingModeFast()
    ->startingPoint(52.5160, 13.3779)
    ->destination(52.5185, 13.4283)
    ->returnInstructions()
    ->langIta()
    ->getDefaultActions();

foreach ($routingActions as $key => $action) {
    echo " - ".$action->instruction . PHP_EOL;
}


