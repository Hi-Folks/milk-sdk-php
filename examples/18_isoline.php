<?php

require __DIR__ . "/../vendor/autoload.php";

use Heremaps\FlexiblePolyline\FlexiblePolyline;
use HiFolks\Milk\Here\RestApi\Isoline;
use HiFolks\Milk\Utils\Environment;
use HiFolks\Milk\Utils\GeoJson;


Dotenv\Dotenv::createImmutable(__DIR__ . "/../")->load();
$hereApiKey = Environment::getEnv('HERE_API_KEY');
$center = [41.890251, 12.492373];
$i = Isoline::instance()
    ->setApiKey($hereApiKey)
    ->originPoint($center[0], $center[1])
    ->byFoot()
    ->rangeByTime(30 * 60) // 30 minutes
    ->get();



if ($i->isError()) {
    echo "Error: " . $i->getErrorMessage();
} else {
    $items = $i->getData()->isolines;
    $geo = new GeoJson();
    foreach ($items as $key => $item) {
        foreach ($item->polygons as $polygon) {
            $geo->addPolygonFromExtendedPolyline($polygon->outer);
        }
    }
    $geo->addPoint($center[0], $center[1]);
    echo $geo->getString();
}
