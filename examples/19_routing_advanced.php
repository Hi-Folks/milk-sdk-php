<?php
require __DIR__ . "/../vendor/autoload.php";

use HiFolks\Milk\Here\RestApi\RoutingV8;
use HiFolks\Milk\Utils\Environment;

Dotenv\Dotenv::createImmutable(__DIR__ . "/../")->load();
$hereApiKey = Environment::getEnv('HERE_API_KEY');

$whenStart = "+24 hour";

$routing = RoutingV8::instance()
    ->setApiKey($hereApiKey)
    ->enableGeocoding()
    ->originAddress("Duomo, Milan, Italy")
    ->destinationAddress("Colosseum, Rome, Italy")
    ->departureTime("+24 hour");

$routingActions = $routing->getDefaultActions();

$totalDuration = 0;
foreach ($routingActions as $key => $action) {
    echo " - ".$action->instruction . " (" . $action->duration .")".PHP_EOL;
    $totalDuration = $totalDuration + $action->duration;
}
echo "When  : ". $whenStart  . PHP_EOL;
echo "Total : " . gmdate("H\h:i\m:s\s", $totalDuration) . PHP_EOL;
echo $routing->getUrl() . PHP_EOL;


