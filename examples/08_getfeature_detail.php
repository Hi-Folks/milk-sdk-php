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
    echo "------------------------" . PHP_EOL;
}

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

$spaceId = "zwtPDoOU";
$featureId = "5a07d0ed16707af6bf09ac23e591ddc5";

$xyzToken = $_ENV['XYZ_ACCESS_TOKEN'];

$xyzSpaceFeature = XyzSpaceFeature::instance($xyzToken);
$result = $xyzSpaceFeature->feature($featureId, $spaceId)->get();
if ($result->isError()) {
    echo "Error: " . $result->getErrorMessage();
} else {
    Obj::echo($result->getData());
    echo "------------------------" . PHP_EOL;
}

echo PHP_EOL;
