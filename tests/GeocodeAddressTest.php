<?php

namespace Markuskooche\Geocode\Tests;

use Markuskooche\Geocode\Exceptions\AddressNotFoundException;
use Markuskooche\Geocode\Exceptions\InvalidCoordinateException;
use Markuskooche\Geocode\Exceptions\ResponseFailedException;
use Markuskooche\Geocode\Geocode;
use PHPUnit\Framework\TestCase;

/**
 * @author Markus Koch
 * @license MIT
 * @package Markuskooche\Geocode
 */
class GeocodeAddressTest extends TestCase
{
    /**
     * @test
     * @throws InvalidCoordinateException
     * @throws ResponseFailedException
     * @throws AddressNotFoundException
     */
    function it_is_the_address_of_the_white_house() : void
    {
        $longitude = -77.03652952864073;
        $latitude = 38.897675909606384;

        $geocode = new Geocode();
        $response = $geocode->address($longitude, $latitude);

        $this->assertEquals('Pennsylvania Avenue Northwest', $response->get('street'));
        $this->assertEquals('1600', $response->get('number'));
        $this->assertEquals('Washington', $response->get('city'));
        $this->assertEquals('20500', $response->get('zip'));
    }

    /**
     * @test
     * @throws InvalidCoordinateException
     * @throws ResponseFailedException
     * @throws AddressNotFoundException
     */
    function it_is_the_address_of_the_german_parliament() : void
    {
        $longitude = 13.375690194757492;
        $latitude = 52.51827801018423;

        $geocode = new Geocode();
        $response = $geocode->address($longitude, $latitude);

        $this->assertEquals('Platz der Republik', $response->get('street'));
        $this->assertEquals('1', $response->get('number'));
        $this->assertEquals('Berlin', $response->get('city'));
        $this->assertEquals('10557', $response->get('zip'));
    }
}
