# Geocode - A simple wrapper for (reverse) geocoding

## Installation
```
composer require markuskooche/geocode
```

## Usage
``` php
use Markuskooche\Geocode\Geocode;

// Get the longitude and latitude of a location.
$street = 'Pennsylvania Avenue Northwest';
$number = '1917';
$city = 'Washington';
$zip = '20500';
        
$geocode = new Geocode('<api-key>', '<driver>');
$coordinates = $geocode->address($street, $number, $city, $zip);
```

``` php
use Markuskooche\Geocode\Geocode;

// Get the street, number, city and zip of a location.
$longitude = -77.0442641;
$latitude = 38.9004915;
        
$geocode = new Geocode('<api-key>', '<driver>');
$coordinates = $geocode->address($street, $number, $city, $zip);
```


## Licence
Geocode is an open-sourced software licensed under the [MIT license](LICENSE.md).
