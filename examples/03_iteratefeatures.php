<?php

/**
 * /spaces/{spaceId}/iterate
 * Iterates all of the features in the space.
 * The features in the response are ordered so that no feature is returned twice.
 * https://xyz.api.here.com/hub/static/swagger/#/Read%20Features/iterateFeatures
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

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

$spaceId = readline("Space ID : ");
$xyzToken = $_ENV['XYZ_ACCESS_TOKEN'];
/** XyzSpaceFeature $xyzSpaceFeature */
$xyzSpaceFeature = XyzSpaceFeature::instance($xyzToken);
/** @var \HiFolks\Milk\Here\Xyz\Common\XyzResponse $result */
$result = $xyzSpaceFeature->iterate($spaceId)->limit(3)->get();
if ($result->isError()) {
    echo "Error: " . $result->getErrorMessage();
    die();
}
array_walk($result->getData()->features, 'print_row');
echo PHP_EOL;
