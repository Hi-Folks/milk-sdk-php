<?php
require __DIR__ . "/../vendor/autoload.php";

use HiFolks\Milk\Here\RestApi\Weather;


Dotenv\Dotenv::createImmutable(__DIR__ . "/../")->load();

$hereApiKey = $_ENV['HERE_API_KEY'];

$hereAppId = $_ENV['HERE_APP_ID'];
$hereAppCode = $_ENV['HERE_APP_CODE'];


function print_row($item, $key)
{
    echo $key + 1 . " " . $item->id . " " . $item->owner . " " . $item->title . "\n";
}

$w = Weather::instance($hereApiKey);

//$w->setAppIdAppCode($hereAppId, $hereAppCode);
$jsonWeather = $w
                ->productAlerts()
                ->name("Berlin")
                ->getJson();

$w->debug();

var_dump($jsonWeather);
