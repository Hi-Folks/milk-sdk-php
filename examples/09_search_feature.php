<?php

/**
 * /spaces/{spaceId}/features/{featureId}
 * Retrieves the feature with the provided identifier.
 * https://xyz.api.here.com/hub/static/swagger/#/Read%20Features/getFeature
 *
 */
require __DIR__ . "/../vendor/autoload.php";

use HiFolks\Milk\Here\Xyz\Space\XyzSpaceFeature;
use HiFolks\Milk\Utils\Obj;

function print_row($item, $key)
{
    Obj::echo($item);
    Obj::echo($item->properties);
    echo "------------------------" . PHP_EOL;
}

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__."/../");
$dotenv->load();

$spaceId = "sSQRaPFS";

$xyzToken = $_ENV['XYZ_ACCESS_TOKEN'];

$xyzSpaceFeature = XyzSpaceFeature::instance($xyzToken);
$xyzSpaceFeature->cleanSearchParams();
$xyzSpaceFeature->addSearchParams("p.cad", 62, "=");
$result = $xyzSpaceFeature->search($spaceId)->get();
if ($result->isError()) {
    echo "Error: ". $result->getErrorMessage();
} else {

    array_walk($result->getData()->features, 'print_row');

}
$xyzSpaceFeature->debug();

echo PHP_EOL;
