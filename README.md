# Geocode - A simple wrapper for (reverse) geocoding

## Installation
```
composer require markuskooche/geocode
```

## Usage
### How to geocode
``` php
use Illuminate\Support\Collection;
use Markuskooche\Geocode\Geocode;

// Get the longitude and latitude of a location.
$street = 'Pennsylvania Avenue Northwest';
$number = '1917';
$city = 'Washington';
$zip = '20500';
      
// You only need api key for the google maps driver.
// The standard driver will use the openstreet maps api.  
$geocode = new Geocode(<driver_instance>);
$coordinates = $geocode->coordinates($street, $number, $city, $zip);
```
``` php
/**
 * The coordinates is returned as a collection.
 * @var Collection $coordinates
 */
$coordinates = [
    'longitude' => '-77.037852'
    'latitude'  => '38.898556',
];
```

### How to reverse geocode
``` php
use Illuminate\Support\Collection;
use Markuskooche\Geocode\Geocode;

// Get the street, number, city and zip of a location.
$longitude = -77.0442641;
$latitude = 38.9004915;

// You only need api key for the google maps driver.
// The standard driver will use the openstreet maps api.
$geocode = new Geocode(<driver_instance>);
$address = $geocode->address($longitude, $latitude);
```
``` php
/**
 * The address is returned as a collection.
 * @var Collection $address
 */
$address = [
    'street' => 'Pennsylvania Avenue Northwest',
    'number' => '1917',
    'city'   => 'Washington',
    'zip'    => '20500',
];
```

### Export the configuration
``` php
php artisan vendor:publish --provider="Markuskooche\Geocode\GeocodeServiceProvider" --tag="config"
```


## Licence
Geocode is an open-sourced software licensed under the [MIT license](LICENSE.md).
