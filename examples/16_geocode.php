<?php
require __DIR__ . "/../vendor/autoload.php";

use HiFolks\Milk\Here\RestApi\Geocode;
use HiFolks\Milk\Utils\Environment;


Dotenv\Dotenv::createImmutable(__DIR__ . "/../")->load();
$hereApiKey = Environment::getEnv('HERE_API_KEY');


    $geocode = Geocode::instance()
        ->setApiKey($hereApiKey)
        ->country("Italia")
        ->q("Colosseo")
        ->langIta();



$r = $geocode->get();
if ($r->isError()) {
    echo "Error: ". $r->getErrorMessage();
} else {
    $items = $r->getData()->items;
    foreach ($items as $key => $item) {
        echo " - " .$item->title. " : ".$item->position->lat . "," . $item->position->lng . PHP_EOL;
    }
}
