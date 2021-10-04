<?php

require __DIR__ . "/../vendor/autoload.php";

use HiFolks\Milk\Here\Xyz\Space\XyzSpace;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

$xyzToken = $_ENV['XYZ_ACCESS_TOKEN'];
$space = XyzSpace::instance($xyzToken);
$timestamp = date("Y-m-d H:i:s", strtotime("now"));
$result = $space->create("My Space " . $timestamp, "Description " . $timestamp);

$space->debug();
if ($result->isError()) {
    echo "SPACE CREATION error: " . $result->getErrorMessage();
} else {
    $result =  $result->getData();
    echo "SPACE CREATED: " . $result->id;
}
