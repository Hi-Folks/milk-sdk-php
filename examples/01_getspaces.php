<?php
require __DIR__ . "/../vendor/autoload.php";

use HiFolks\Milk\Here\Xyz\Common\XyzConfig;
use HiFolks\Milk\Here\Xyz\Space\XyzSpace;
use HiFolks\Milk\Utils\Environment;

function print_row($item, $key)
{
    echo $key + 1 . " " . $item->id . " " . $item->owner . " " . $item->title . "\n";
}


Dotenv\Dotenv::createImmutable(__DIR__ . "/../")->load();
$xyzToken = Environment::getEnv( 'XYZ_ACCESS_TOKEN');

$xyzHost = Environment::getEnv( 'XYZ_API_HOSTNAME');
$config= XyzConfig::getInstance($xyzToken, $xyzHost);

$space = new XyzSpace($config);
echo "GET" . PHP_EOL;

$s = $space->get();

if ($s->isError()) {
    echo "Error: " . $s->getErrorMessage(). PHP_EOL;
    $space->debug();
    die();
} else {
    echo $space->getUrl(). PHP_EOL;
    $space->debug();
    $a = $s->getData();
    array_walk($a, 'print_row');
}

echo "GET OWNER ALL" . PHP_EOL;
$space->reset();
$a =  $space->ownerAll()->getLimited(2);

$space->debug();
array_walk($a, 'print_row');


echo "GET OTHERS" . PHP_EOL;
$space->reset();
$s =  $space->ownerOthers()->getLimited(2);
array_walk($s, 'print_row');

echo "GET INCLUDE RIGHTS" . PHP_EOL;
$space->reset();
$s =  $space->ownerOthers()->includeRights()->getLimited(2);
array_walk($s, 'print_row');
$space->debug();

$space->reset();
$time_start = microtime(true);
$space->get();
echo 'Total execution time in seconds: ' . (microtime(true) - $time_start);
$time_start = microtime(true);
$space->cacheResponse(true)->get();
echo 'Total execution time in seconds: ' . (microtime(true) - $time_start);
$time_start = microtime(true);
$space->cacheResponse(true)->get();
echo 'Total execution time in seconds: ' . (microtime(true) - $time_start);
