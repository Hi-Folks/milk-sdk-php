<?php

/**
 * /spaces/{spaceId}
 * Returns the space definition
 * https://xyz.api.here.com/hub/static/swagger/#/Read%20Spaces/getSpace
 */

require __DIR__ . "/../vendor/autoload.php";

use HiFolks\Milk\Here\Xyz\Space\XyzSpace;
use HiFolks\Milk\Utils\Obj;


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

$spaceId = readline("Space ID : ");
$xyzToken = $_ENV['XYZ_ACCESS_TOKEN'];
/** XyzSpace $xyzSpace */
$xyzSpace = XyzSpace::instance($xyzToken);
$xyzSpace->reset();
$o1 = $xyzSpace->spaceId($spaceId)->get();
if ($o1->isError()) {
    echo "Error" . $o1->getErrorMessage();
    die();
}
Obj::echo($o1->getData());
