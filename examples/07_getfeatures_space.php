<?php

/**
 * /spaces/{spaceId}/features
 * Returns all of the features found for the provided list of ids. The response is always a FeatureCollection, even if there are no features with the provided ids.
 * https://xyz.api.here.com/hub/static/swagger/#/Read%20Features/getFeatures
 *
 */
require __DIR__ . "/../vendor/autoload.php";

use HiFolks\Milk\Here\Xyz\Space\XyzSpaceFeature;
use HiFolks\Milk\Utils\Obj;

function print_row($item, $key)
{
    Obj::echo($item);
    echo "------------------------" . PHP_EOL;
}

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__."/../");
$dotenv->load();

$spaceId = "zwtPDoOU";
$featureIds = [
    "5a07d0ed16707af6bf09ac23e591ddc5",
    "0f995f1915ec5057692dff890539774f"
];

$xyzToken = $_ENV['XYZ_ACCESS_TOKEN'];

$xyzSpaceFeature = XyzSpaceFeature::instance($xyzToken);
$result = $xyzSpaceFeature->features($spaceId)->featureIds($featureIds)->get();
if ($result->isError()) {
    echo "Error:" . $result->getErrorMessage();
} else {
    $a = $result->getData();
    array_walk($a->features, 'print_row');


}
echo PHP_EOL;
