<?php
require __DIR__ . "/../vendor/autoload.php";

use HiFolks\Milk\Here\RestApi\RoutingV8;


Dotenv\Dotenv::createImmutable(__DIR__ . "/../")->load();
$hereApiKey = $_ENV['HERE_API_KEY'];


function print_row($item, $key)
{
    echo $key + 1 . " " . $item->id . " " . $item->owner . " " . $item->title . "\n";
}

$routing = RoutingV8::instance($hereApiKey)
    ->byCar()
    ->routingModeFast()
    ->startingPoint(52.5160, 13.3779)
    ->destination(52.5185, 13.4283)
    ->returnInstructions()
    ->langIta();




//echo $routing->getUrl();

//echo $routing->getJson();

//var_dump($routing->get());
$r = $routing->get();
//var_dump($r);
$actions = $r->routes[0]->sections[0]->actions;
foreach ($actions as $key => $action) {
    echo " - ".$action->instruction . PHP_EOL;
}
//var_dump($routing->debug());
