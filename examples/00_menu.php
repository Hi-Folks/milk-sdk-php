<?php

require __DIR__ . "/../vendor/autoload.php";

use HiFolks\Milk\Here\Xyz\Space\XyzSpace;
use HiFolks\Milk\Here\Xyz\Space\XyzSpaceStatistics;
use HiFolks\Milk\Here\Xyz\Space\XyzSpaceFeature;
use HiFolks\Milk\Utils\Obj;

Dotenv\Dotenv::createImmutable(__DIR__ . "/../")->load();
$xyzToken = $_ENV['XYZ_ACCESS_TOKEN']; //getenv('XYZ_ACCESS_TOKEN');
print_r(getenv());
echo "-" . $xyzToken . "-";
$spaceId = "";
$limitPage = 5;


function print_row($item, $key)
{
    echo $key + 1 . " " . $item->id . " " . $item->owner . " " . $item->title . "\n";
}
function print_obj($item, $key)
{
    Obj::echo($item);
    echo "------------------------" . PHP_EOL;
}

function print_menu()
{
    echo "============================" . PHP_EOL;
    echo "* Select                   *" . PHP_EOL;
    echo "============================" . PHP_EOL;
    echo "* s     -> List your Space" . PHP_EOL;
    echo "* sa    -> List all Spaces" . PHP_EOL;
    echo "* so    -> List other Spaces" . PHP_EOL;
    echo "* sp    -> Set current space id" . PHP_EOL;
    echo "* si    -> iterate features" . PHP_EOL;
    echo "* v     -> View you state (spaceid, token etc)" . PHP_EOL;
    echo "* h     -> View this menu" . PHP_EOL;
    echo "============================" . PHP_EOL;
}

$choice = "h";
while ($choice !== "q") {
    switch ($choice) {
        case "sp":
            echo "Setting your space ID, current is (" . $spaceId . ")" . PHP_EOL;
            $tempSpaceId = readline("Space ID:");
            if (strlen($tempSpaceId) > 0) {
                $spaceId = $tempSpaceId;
                echo "Your space ID is: (" . $spaceId . ")" . PHP_EOL;
            } else {
                echo "Your space ID is not changed (" . $spaceId . ")" . PHP_EOL;
            }
            break;
        case "st":
            if ($spaceId == "") {
                echo "Please insert your space id (sp)" . PHP_EOL;
            } else {
                echo "Calculating statistics for spacdeid (" . $spaceId . ")" . PHP_EOL;
                $o1 = XyzSpaceStatistics::instance($xyzToken)->spaceId($spaceId)->get();
                echo "The Space has {$o1->count->value} features " . PHP_EOL;
                echo $o1->count->estimated ? "The count is estimated" : "The count is real:)";
                echo PHP_EOL;
                echo "The Space is {$o1->byteSize->value} bytes " . PHP_EOL;
                echo $o1->byteSize->estimated ? "The size is estimated" : "The size is real:)";
                echo PHP_EOL;
            }
            break;
        case "si":
            echo "Iterating features of " . $spaceId . " space" . PHP_EOL;
            $xyzSpaceFeature = XyzSpaceFeature::instance($xyzToken);
            $result = $xyzSpaceFeature->iterate($spaceId)->limit($limitPage)->get();
            if ($result->isError()) {
                echo "Error: " . $result->getErrorMessage();
            } else {
                array_walk($result->getData()->features, 'print_obj');
            }

            break;



        case 's':
            echo "List your spaces" . PHP_EOL;
            $space = XyzSpace::instance($xyzToken);

            $s = $space->get();

            if ($s->isError()) {
                echo "Error:" . $s->getErrorMessage();
            } else {
                $a = $s->getData();
                array_walk($a, 'print_row');
            }


            break;
        case 'sa':
            echo "List all spaces" . PHP_EOL;
            $space = XyzSpace::instance($xyzToken);
            $s =  $space->ownerAll()->getLimited($limitPage);
            array_walk($s, 'print_row');
            break;
        case 'so':
            echo "List others spaces" . PHP_EOL;
            $space = XyzSpace::instance($xyzToken);
            $s =  $space->ownerOthers()->getLimited($limitPage);
            array_walk($s, 'print_row');
            break;

        default:
            print_menu();
            break;
    }

    $choice = readline("Make your choice:");
}
