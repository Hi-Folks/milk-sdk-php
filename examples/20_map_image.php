<?php
require __DIR__ . "/../vendor/autoload.php";

use HiFolks\Milk\Here\RestApi\MapImage;

Dotenv\Dotenv::createImmutable(__DIR__ . "/../")->load();
$hereApiKey = $_ENV['HERE_API_KEY'];

function print_line($label, $value ="", $delimiterValue="")
{
    echo " * " . $label;
    if ($value !== "") {
        echo ": " . $delimiterValue . $value . $delimiterValue . PHP_EOL;
    }
}

/*$image = MapImage::instance($hereApiKey)
    ->centerAddress("Vicenza")
    ->zoom(12)
    ->getUrl();
print_line("Image", $image);
*/
$image = MapImage::instance($hereApiKey)
    ->center(45.548,11.54947)
    ->zoom(8)
    ->getUrl();
print_line("Image", $image);

$image = MapImage::instance($hereApiKey)
    ->center(45.548,11.54947)
    ->addPoi(45,12 ,"ff0000")
    ->addPoi(45.1,12.1 ,"00ff00")
    ->addPoi(45.2,12.2 ,"0000ff","","12", "Test 3")
    ->zoom(8)
    ->getUrl();

print_line("Image", $image);
