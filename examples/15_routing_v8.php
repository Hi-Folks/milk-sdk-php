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
    ->byBicycle()
    ->routingModeFast()
    ->startingPoint(52.5160, 13.3779)
    ->destination(52.5185, 13.4283)
    ->returnInstructions()
    ->langIta()
    ->getDefaultActions();

foreach ($routingActions as $key => $action) {
    echo " - " . $action->instruction . PHP_EOL;
}


$routing = RoutingV8::instance()
    ->setApiKey($hereApiKey)
    ->byBicycle()
    ->startingPoint(52.5160, 13.3779)
    ->destination(52.5185, 13.4283)
    ->returnInstructions()
    ->alternatives(3)
    ->langIta()
    ->get();

$d = $routing->getData();

foreach ($d->routes as $key => $route) {
    var_dump($route->sections);
    foreach ($route->sections as $section) {
        echo "----- SECTION " . $section->id . " (" . $section->type . ")" . PHP_EOL;
        foreach ($section->actions as $key => $action) {
            echo " --- " . $action->instruction . PHP_EOL;
            echo "     Action: " . $action->action .
                "; Duration:" . $action->duration .
                "; Length:" . $action->length .
                "; Offset:" . $action->offset . PHP_EOL ;
                //"; Direction:" . $action->direction .
                //"; Exit:" . $action->exit . PHP_EOL;
        }
    }
}



$routing = RoutingV8::instance()
    ->setApiKey($hereApiKey)
    ->startingPoint(47.257410, 11.351458)
    ->destination(47.168076, 11.861380)
    ->avoidAreaByCenter(52, 13, 30)
    //->avoidArea(13.082,52.416,13.628,52.626)
    ->byCar()
    ->returnInstructions()
    ->getDefaultActions();

var_dump($routing);
