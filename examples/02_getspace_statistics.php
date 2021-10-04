<?php

require __DIR__ . "/../vendor/autoload.php";

use HiFolks\Milk\Here\Xyz\Space\XyzSpaceStatistics;

function print_row($item, $key)
{
    echo $key + 1 . " " . $item->id . " " . $item->owner . " " . $item->title . "\n";
}
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

$spaceId = readline("Space ID : ");
$xyzToken = $_ENV['XYZ_ACCESS_TOKEN'];

$ss = XyzSpaceStatistics::instance($xyzToken)->spaceId($spaceId)->get();
if ($ss->isError()) {
    echo "Error: " . $ss->getErrorMessage();
    die();
}

$o1 = $ss->getData();
echo "The Spaces has {$o1->count->value} features " . PHP_EOL;
echo $o1->count->estimated ? "The count is estimated" : "The count is real:)";
echo PHP_EOL;

echo "The Spaces is {$o1->byteSize->value} byte " . PHP_EOL;
echo $o1->byteSize->estimated ? "The size is estimated" : "The size is real:)";
echo PHP_EOL;
