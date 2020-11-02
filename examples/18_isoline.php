<?php
require __DIR__ . "/../vendor/autoload.php";

use Heremaps\FlexiblePolyline\FlexiblePolyline;
use HiFolks\Milk\Here\RestApi\Isoline;
use HiFolks\Milk\Utils\Environment;


Dotenv\Dotenv::createImmutable(__DIR__ . "/../")->load();
$hereApiKey = Environment::getEnv('HERE_API_KEY');

$i = Isoline::instance()
    ->setApiKey($hereApiKey)
    ->originPoint(41.890251, 12.492373)
    ->byFoot()
    ->rangeByTime(600) // 10 minutes
    ->get();



if ($i->isError()) {
    echo "Error: ". $i->getErrorMessage();
} else {
    $items = $i->getData()->isolines;
    foreach ($items as $key => $item) {
        foreach ($item->polygons as $polygon) {
            $data = FlexiblePolyline::decode($polygon->outer);
            var_dump($data);
            foreach ($data["polyline"] as $p) {
                echo "[" . $p[1]. "," . $p[0] . "],".PHP_EOL;
            }
        }

    }
}
