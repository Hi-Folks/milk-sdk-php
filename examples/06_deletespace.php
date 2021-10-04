<?php

require __DIR__ . "/../vendor/autoload.php";

use HiFolks\Milk\Here\Xyz\Space\XyzSpace;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

$spaceId = readline("Space ID : ");
$xyzToken = $_ENV['XYZ_ACCESS_TOKEN'];
/** XyzSpace $xyzSpace */
$xyzSpace = XyzSpace::instance($xyzToken);
$o1 = $xyzSpace->delete($spaceId);
if ($o1->isError()) {
    echo "Error deleting space: " . $o1->getErrorMessage();
} else {
    echo $o1->getDataAsJsonString();
}
