# Changelog

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
 
    

