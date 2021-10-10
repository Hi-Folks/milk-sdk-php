<?php

require __DIR__ . "/../vendor/autoload.php";

use HiFolks\Milk\Here\RestApi\Discover;

Dotenv\Dotenv::createImmutable(__DIR__ . "/../")->load();
$hereApiKey = $_ENV['HERE_API_KEY'];

function print_row($item, $key)
{
    echo $key + 1 . " " . $item->id . " " . $item->owner . " " . $item->title . "\n";
}

$address = "Basilica San Marco,  venezia";
$discover = Discover::instance($hereApiKey)
    ->q($address)
    ->at(41.902782, 12.496366)
    ->inCountry("ITA")
    ->get();

//var_dump($discover->getData());
echo "I Was searching: " . $address . PHP_EOL;
foreach ($discover->getDataObject()->items as $item) {
    echo "Normalized and valid address: " . $item->address->label . PHP_EOL;
}

$address = "Basilica San Marco,  venezia";
$discover = Discover::instance($hereApiKey)
    ->q($address)
    ->inItaly()
    ->get();

//var_dump($discover->getData());
echo "I Was searching: " . $address . PHP_EOL;
foreach ($discover->getDataObject()->items as $item) {
    echo "Normalized and valid address: " . $item->address->label . PHP_EOL;
}

