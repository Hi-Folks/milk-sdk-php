# Milk SDK PHP

[![Actions Status](https://github.com/hi-folks/milk-sdk-php/workflows/PHP%20check%20test/badge.svg)](https://github.com/hi-folks/milk-sdk-php/actions)
[![GitHub license](https://img.shields.io/github/license/hi-folks/milk-sdk-php)](https://github.com/hi-folks/milk-sdk-php/blob/master/LICENSE.md)

Milk SDK PHP is a (fluent) open-source PHP library that makes it easy to integrate your PHP application with location services like:
- HERE **Data Hub** API (was XYZ Api);
- HERE **Routing** API (**V8** and **V7**);
- HERE **Weather** Destination API;
- HERE **Geocoding** API;
- HERE **Reverse Geocoding** API;
- HERE **Isoline** API;

## Getting Started

### Install the SDK

In your PHP project install package via Composer:

```sh
composer require hi-folks/milk-sdk-php
```

### Configuring XYZ HUB

With this SDK you can consume DataHub (XYZ) API.
You have 2 options:
- use your own instance of XYZ HUB
or
- use Data Hub Cloud https://developer.here.com/documentation/studio/map_customization_suite_hlp/dev_guide/index.html

#### Configure SDK with your own instance of XYZ HUB

Running your own instance of XYZ HUB means that you already have your instance of https://github.com/heremaps/xyz-hub.
A tutorial about how to set up XYZ Hub locally (on localhost): https://dev.to/robertobutti/restful-web-service-for-geospatial-data-12ii

Create a _.env_ file.
Fill it with:

```
XYZ_ACCESS_TOKEN=""
XYZ_API_HOSTNAME="http://localhost:8080"
```

#### Configure SDK with XYZ HUB Cloud service

Using XYZ HUB Cloud Service means that you are using this host https://xyz.api.here.com.

To use this service you need to sign in as developer on https://developer.here.com/ and create your plan (for example Freemium) and obtain your Access Token.

Once you have your access token, create a _.env_ file. You can start from a sample file (_.env.dist_):

```sh
cp .env.dist .env
```

Then, you need to fill your XYZ_ACCESS_TOKEN in .env file with your access token.

## Quick Examples

In order to use the Milk SDK, you need to:

- create a PHP file
- include the autoload.php file
- declare all imports via _use_
- load environment configuration (via _Dotenv_)
- get your token
- get your XYZ Spaces
- display your result

```php
<?php
// include the autoload.php file
require "./vendor/autoload.php";
// declare all imports via "use"
use HiFolks\Milk\Here\Xyz\Space\XyzSpace;
// set your token
$xyzToken = "your xyz space token";
// Get your XYZ Spaces (XyzResponse class)
$s = XyzSpace::instance($xyzToken)->get();
// display your result
var_dump($s->getData());
```

### Retrieve your XYZ Spaces

To get your XYZ Spaces:

```php
$s = XyzSpace::instance($xyzToken)->get();
```

To get XYZ Spaces by everybody (not only your own XYZ Spaces):

```php
$s =  XyzSpace::instance($xyzToken)->ownerAll()->get();
```

### Delete Space

To delete a XYZ Space:

```php
$xyzSpaceDeleted = XyzSpace::instance($xyzToken)->delete($spaceId);
```

### Create Space

To create a new XYZ Space:

```php
$xyzSpaceCreated = XyzSpace::instance($xyzToken)->create("My Space", "Description");
```

### Update Space

To update the XYZ Space with space id == \$spaceId:

```php
$obj = new \stdClass;
$obj->title = "Edited Title";
$obj->description = "Edited Description";
$retVal = $space->update($spaceId, $obj);
```

### Statistics

The get statistics from XYZ Space:

```php
$statistics =  XyzSpaceStatistics::instance($xyzToken)->spaceId($spaceId)->get();
```

### Features

Iterate features

```php
/** XyzSpaceFeature $xyzSpaceFeature */
$xyzSpaceFeature = new XyzSpaceFeature::instance($xyzToken);
$result = $xyzSpaceFeature->iterate($spaceId)->get();
```

### Retrieve 1 Feature

You need to use feature() method with $featureId and $spaceId

```php
$xyzSpaceFeature = XyzSpaceFeature::instance($xyzToken);
$result = $xyzSpaceFeature->feature($featureId, $spaceId)->get();
```

### Create or Edit 1 Feature

To create or edit a Feature you can use saveOne() method.


```php
$spaceId = "yourspaceid";
$featureId = "yourfeatureid";
$geoJson = new GeoJson();
$properties = [
    "name" => "Berlin",
    "op" => "Put"
];
$geoJson->addPoint(52.5165, 13.37809, $properties, $featureId);
$feature = XyzSpaceFeatureEditor::instance($xyzToken);
$result = $feature->feature($geoJson->get())->saveOne($spaceId, $featureId);
$feature->debug();
```

### Create multiple features from a geojson file

If you have a Geojson File, you can upload it into a space.

```php
$spaceId = "yourspaceid";
$file = "https://data.cityofnewyork.us/api/geospatial/arq3-7z49?method=export&format=GeoJSON";
$response = XyzSpaceFeatureEditor::instance($xyzToken)
    ->addTags(["file"])
    ->geojson($file)
    ->create($spaceId);
```

### Search features by property

To search features by properties you can use *addSearchParams* to add serach params, in the example below, you are searching features with *name* property equals "Colosseo".

```php
$spaceId = "yourspaceid";
$xyzSpaceFeature = XyzSpaceFeature::instance($xyzToken)->addSearchParams("p.name", "Colosseo");
$result = $xyzSpaceFeature->search($spaceId)->get();
```
### Search features by proximity

To search feature close to latitude=41.890251 and longitude=12.492373 with a radius less than 1000 meters (close to Colosseum):

```php
$spaceId = "yourspaceid";
$result = XyzSpaceFeature::instance($xyzToken)->spatial($spaceId,  41.890251, 12.492373,  1000)->get();
```

## Obtain HERE API Key
To use HERE Location Services you will need an API key. The API key is a unique identifier that is used to authenticate API requests associated with your project.
There is a official tutorial for retrieving the API Key:
https://developer.here.com/tutorials/getting-here-credentials/

## Weather API

To retrieve weather forecasts in Berlin:

```php
$jsonWeather = Weather::instance()
    ->setAppIdAppCode($hereAppId, $hereAppCode)
    ->productForecast7days()
    ->name("Berlin")
    ->get();
var_dump($jsonWeather->getData());
var_dump($jsonWeather->isError());
var_dump($jsonWeather->getErrorMessage());
var_dump($jsonWeather->getDataAsJsonString());
```

## Routing API (v7)
To retrieve the fastest route by foot

```php
$r = (new RoutingV7())
    ->setApiKey($hereApiKey)
    ->byFoot()
    ->typeFastest()
    ->startingPoint(52.5160, 13.3779)
    ->destination(52.5185, 13.4283)
    ->get();
var_dump($r->getData());
var_dump($r->isError());
var_dump($r->getErrorMessage());
var_dump($r->getDataAsJsonString());
```

Instead of using get(), you could use _getManeuverInstructions()_ method:

```php
$r = (new RoutingV7())
    ->setApiKey($hereApiKey)
    ->byFoot()
    ->typeFastest()
    ->startingPoint(52.5160, 13.3779)
    ->destination(52.5185, 13.4283)
    ->getManeuverInstructions();

var_dump($r);
```

## Routing API (v8)
To retrieve the fastest route by car

```php
$routingActions = RoutingV8::instance()
    ->setApiKey($hereApiKey)
    ->byCar()
    ->routingModeFast()
    ->startingPoint(52.5160, 13.3779)
    ->destination(52.5185, 13.4283)
    ->returnInstructions()
    ->langIta()
    ->getDefaultActions();

foreach ($routingActions as $key => $action) {
    echo " - ".$action->instruction . PHP_EOL;
}
```

## Geocoding API
In order to retrieve geo-coordinates (latitude, longitude) of a known address or place.

```php
use HiFolks\Milk\Here\RestApi\Geocode;
$hereApiKey = "Your API KEY";
$r = Geocode::instance()
    ->setApiKey($hereApiKey)
    ->country("Italia")
    ->q("Colosseo")
    ->langIta()
    ->get();
var_dump($r->getData());
var_dump($r->isError());
var_dump($r->getErrorMessage());
var_dump($r->getDataAsJsonString());
```

## Reverse Geocoding API
In order to find the nearest address to specific geo-coordinates:

```php
use HiFolks\Milk\Here\RestApi\ReverseGeocode;
$hereApiKey = "Your API KEY";
$r = ReverseGeocode::instance()
    ->setApiKey($hereApiKey)
    ->at(41.88946,12.49239)
    ->limit(5)
    ->lang("en_US")
    ->get();
var_dump($r->getData());
var_dump($r->isError());
var_dump($r->getErrorMessage());
var_dump($r->getDataAsJsonString());

if ($r->isError()) {
    echo "Error: ". $r->getErrorMessage();
} else {
    $items = $r->getData()->items;
    foreach ($items as $key => $item) {
        echo " - " .$item->title.
            " : ( ".$item->position->lat . "," . $item->position->lng .
            " ) , distance:" . $item->distance . " , type: " . $item->resultType . PHP_EOL;
    }
}
```

## Isoline API
```php
use HiFolks\Milk\Here\RestApi\Isoline;
$hereApiKey = "yourapikey";
$isoline = Isoline::instance()
    ->setApiKey($hereApiKey)
    ->originPoint(41.890251, 12.492373)
    ->byFoot()
    ->rangeByTime(600) // 10 minutes
    ->get();

```
## Map Image Api
With MapImage class you can create static image of a map.
For the map you can define:
- *center()*: the center of the map;
- *addPoi()*: add a point in the map;
- *zoom()*: set the zoom level;
- *height()*: set the height of image (in pixel);
- *width()*: set the width of the image (in pixel).
```php
use Hifolks\milk\here\RestApi\MapImage;
$hereApiKey = "yourapikey";
$imageUrl = MapImage::instance($hereApiKey)
    ->center(45.548, 11.54947)
    ->addPoi(45, 12, "ff0000")
    ->addPoi(45.1, 12.1, "00ff00")
    ->addPoi(45.2, 12.2, "0000ff", "", "12", "Test 3")
    ->zoom(12)
    ->height(2048)
    ->width(2048 / 1.4)
    ->getUrl();
```

You can use also the Geocoding functionality with *centerAddress()* method.
```php
$image = MapImage::instance($hereApiKey)
    ->centerAddress("Venezia")
    ->zoom(12)
    ->height(2048)
    ->width(intval(2048 / 1.4));
$imageUrl = $image->getUrl();
```


## Useful reference

### Data Hub API

- ReDoc API documentation: https://xyz.api.here.com/hub/static/redoc/
- Open API documentation: https://xyz.api.here.com/hub/static/swagger/

### HERE Destination Weather API

- Overview: https://developer.here.com/documentation/destination-weather/dev_guide/topics/overview.html
- API Reference: https://developer.here.com/documentation/destination-weather/dev_guide/topics/api-reference.html

### HERE Rest Routing V8 API

- Overview: https://developer.here.com/documentation/routing-api/8.9.1/dev_guide/topics/use-cases/calculate-route.html
- API Reference: https://developer.here.com/documentation/routing-api/8.9.1/api-reference-swagger.html

### HERE Rest Geocoding API

- Overview: https://developer.here.com/documentation/geocoding-search-api/dev_guide/topics/endpoint-geocode-brief.html

### HERE Rest Reverse Geocoding API

- Overview: https://developer.here.com/documentation/geocoding-search-api/dev_guide/topics/endpoint-reverse-geocode-brief.html

### HERE Rest Isoline API

- Overview: https://developer.here.com/documentation/isoline-routing-api/dev_guide/index.html

