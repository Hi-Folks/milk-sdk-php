<?php
require __DIR__ . "/../vendor/autoload.php";


use HiFolks\Milk\Here\Xyz\Space\XyzSpaceFeatureEditor;
use HiFolks\Milk\Utils\GeoJson;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

$xyzToken = $_ENV['XYZ_ACCESS_TOKEN'];
$feature = XyzSpaceFeatureEditor::instance($xyzToken);


$geoJson = new GeoJson();
$properties = [
    "name" => "Colosseo",
];

$spaceId = readline("Space ID : ");

$geoJson->addPoint(41.890251, 12.492373, $properties ,1);
$result = $feature->addTags([ "milk"])->create($spaceId, $geoJson->getString());

$feature->debug();
//$result =  json_decode($result->getBody());
//var_dump($result);

$geoJson = new GeoJson();
$properties = [
    "name" => "Colosseo",
    "op" => "Edit"
];
$geoJson->addPoint(41.890251, 12.492373, $properties, "2");
$result = $feature->addTags(["edit"])->edit($spaceId, $geoJson->getString());

$geoJson = new GeoJson();
$properties = [
    "name" => "Berlin",
    "op" => "Edit"
];
$geoJson->addPoint(52.5165, 13.37809, $properties, "3");
$result = $feature->addTags(["edit"])->edit($spaceId, $geoJson->getString());


$feature = XyzSpaceFeatureEditor::instance($xyzToken);
$result = $feature->delete($spaceId, [1,2]);
//var_dump($result);
$feature->debug();

$feature = XyzSpaceFeatureEditor::instance($xyzToken);
//$result = $feature->deleteOne("eFM936rJ", "3");
//var_dump($result);
$feature->debug();

$geoJson = new GeoJson();
$properties = [
    "name" => "Berlin",
    "op" => "Patch"
];
$geoJson->addPoint(52.5165, 13.37809, $properties, "3");
$feature = XyzSpaceFeatureEditor::instance($xyzToken);
$result = $feature->feature($geoJson->get())->editOne($spaceId, "3");
$feature->debug();
var_dump($result);


$geoJson = new GeoJson();
$properties = [
    "name" => "Berlin",
    "op" => "Put"
];
$geoJson->addPoint(52.5165, 13.37809, $properties, "3");
$feature = XyzSpaceFeatureEditor::instance($xyzToken);
$result = $feature->feature($geoJson->get())->saveOne($spaceId, "3");
$feature->debug();
var_dump($result);
