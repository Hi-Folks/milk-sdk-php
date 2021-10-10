# Changelog
## 0.1.6 - 2021-10-10
### Add
- HERE Discover API integration. Now you can search and validate an address via Discover API

### Hacktoberfest
- Type casts for RoutingV8, Geocode, Isoline, thanks to @kjellknapen
- Avoid duplicate code for adding api key in src/Here/RestApi/* files, thanks to @JohnAyling1979
- creating a new cover image.

## 0.1.5 - 2021-10-06
### Add
- HERE Map Api integration. Now you can generate static image for map using HERE Map Image
### Hacktoberfest
- Update some examples;
- update GitHub Actions workflow;
- Update CONTRIBUTING.md, thanks to @iridacea
- Create CODE_OF_CONDUCT.md;
- Update Isoline.php for type declarations, thanks to @KamaZz
- Add Map Image API usage documentation, thanks to @soil-droid

## 0.1.4 - 2021-05-02
### Add
- Added some tests for Utils;

### Change
- Removed support for PHP7.3;
- Improved code quality also for /tests and refactoring stuff (based on feedback provided by code quality phpcs + phpstan);
- Update GitHub Actions Workflow by Ghygen (https://ghygen.hi-folks.dev/);


## 0.1.3 - 2021-04-15
### Add
- Add PHP 8 compatibility
- Add new Workflow for Github Actions
- Add new examples for geocoding and authentication

## 0.1.2 - 2020-11-06
### Add
- Routing V8: add avoid param, thanks to @torelafes;
- Routing V8: add helper methods for avoidFerry, avoidTunnel, avoidDirtRoad etc;
- Routing V8: add departureTime param, thanks to @torelafes;
- Implement Bbox class;
- Routing V8: add auto geocoding in routing, you can use address and not just latitude longitude;
- Add Extended polyline encoding/decoding, thanks to https://github.com/rozklad/heremaps-flexible-polyline;
- Isoline: export **GeoJSON**;


### Change
- Routing V8: Fix multiple via param;




## 0.1.1 - 2020-10-31

### Add
- Routing V8: add new transport mode "bicycle" and "scooter";
- Routing V8: add alternatives parameter;
- Routing V8: add units (metrics/imperial);
- Routing V8: add via parameter (thanks to @cgarde89);
- Isoline: add Isoline integration;
- Adding test suite;
- LatLong added magic method toString() (thanks to @cgarde89);

### Change
- Fix Phpstan warnings (level 6) (thanks to @creativenull)
- Refactoring base classes avoiding Singleton design pattern, and introducing DI;
- update examples/* and Readme file


## 0.1.0 - 2020-10-21

Initial release.
- Data Hub integration (https://developer.here.com/products/data-hub)
    - Spaces (Create, edit, delete, get)
    - Features (iterate, get)
    - Features Editor (edit,create, delete, add tags)
    - Search Features (spatial)
    - Statistics
- Here Developers APIs integration:
    - Routing V7 (https://developer.here.com/documentation/routing/dev_guide/topics/introduction.html)
    - Routing V8 (https://developer.here.com/documentation/routing-api/8.9.1/dev_guide/index.html)
    - Weather (https://developer.here.com/documentation/destination-weather/dev_guide/topics/overview.html)
    - Geocode (https://developer.here.com/documentation/geocoding-search-api/dev_guide/index.html)
    - Reverse Geocode (https://developer.here.com/documentation/geocoding-search-api/dev_guide/index.html)
 
    

