<?php

namespace Markuskooche\Geocode\Tests;

use Markuskooche\Geocode\Drivers\OpenStreetMap;
use Markuskooche\Geocode\Exceptions\CoordinatesNotFoundException;
use Markuskooche\Geocode\Exceptions\ResponseFailedException;
use Markuskooche\Geocode\Geocode;
use PHPUnit\Framework\TestCase;

/**
 * @author Markus Koch
 * @license MIT
 * @package Markuskooche\Geocode
 */
class GeocodeCoordinatesTest extends TestCase
{
    /**
     * @test
     * @throws ResponseFailedException
     * @throws CoordinatesNotFoundException
     */
    function it_is_the_coordinates_of_the_white_house_openstreet() : void
    {
        $street = 'Pennsylvania Avenue Northwest';
        $number = '1917';
        $city = 'Washington';
        $zip = '20500';

        $geocode = new Geocode(new OpenStreetMap());
        $response = $geocode->coordinates($street, $number, $city, $zip);

        $longitude = (float) $response->get('longitude') ?: null;
        $latitude = (float) $response->get('latitude') ?: null;

        if (is_float($longitude) && is_float($latitude)) {
            $this->assertTrue(float_in_range(-77.0442641, $longitude));
            $this->assertTrue(float_in_range(38.9004915, $latitude));
        } else {
            $this->fail('Coordinates not found');
        }
    }

    /**
     * @test
     * @throws ResponseFailedException
     * @throws CoordinatesNotFoundException
     */
    function it_is_the_coordinates_of_the_german_parliament_openstreet() : void
    {
        $street = 'Platz der Republik';
        $number = '1';
        $city = 'Berlin';
        $zip = '10557';

        $geocode = new Geocode(new OpenStreetMap());
        $response = $geocode->coordinates($street, $number, $city, $zip);

        $longitude = (float) $response->get('longitude') ?: null;
        $latitude = (float) $response->get('latitude') ?: null;

        if (is_float($longitude) && is_float($latitude)) {
            $this->assertTrue(float_in_range(13.3763176, $longitude));
            $this->assertTrue(float_in_range(52.518619, $latitude));
        } else {
            $this->fail('Coordinates not found');
        }
    }
}
