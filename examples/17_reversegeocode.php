<?php
require __DIR__ . "/../vendor/autoload.php";

use HiFolks\Milk\Here\RestApi\ReverseGeocode;
use HiFolks\Milk\Utils\Environment;


Dotenv\Dotenv::createImmutable(__DIR__ . "/../")->load();
$hereApiKey = Environment::getEnv('HERE_API_KEY');

$geocode = ReverseGeocode::instance()
    ->setApiKey($hereApiKey)
    ->at(41.88946,12.49239)
    ->limit(10)
    ->langIta();

$r = $geocode->get();
if ($r->isError()) {
    echo "Error: ". $r->getErrorMessage();
} else {
    $items = $r->getData()->items;
    foreach ($items as $key => $item) {
        echo " - " .$item->title.
            " : ( ".$item->position->lat . "," . $item->position->lng .
            " ) , distance:" . $item->distance . " , type: " . $item->resultType . PHP_EOL;
    }
}
