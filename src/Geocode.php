<?php

namespace Markuskooche\Geocode;

use Illuminate\Support\Collection;
use Markuskooche\Geocode\Drivers\Driver;
use Markuskooche\Geocode\Exceptions\AddressNotFoundException;
use Markuskooche\Geocode\Exceptions\CoordinatesNotFoundException;
use Markuskooche\Geocode\Exceptions\InvalidCoordinateException;
use Markuskooche\Geocode\Exceptions\ResponseFailedException;

/**
 * The geocode class is the main class of the package.
 * It provides the functionality to geocode addresses and coordinates.
 *
 * @author Markus Koch
 * @license MIT
 * @package Markuskooche\Geocode
 */
class Geocode
{
    /** @var Driver */
    protected Driver $driver;

    /**
     * Create a new Geocode instance.
     *
     * @param Driver $driver
     * @return void
     */
    public function __construct(Driver $driver)
    {
        $this->driver = $driver;
    }

    /**
     * Geocode a given address.
     *
     * @param string $street
     * @param string $number
     * @param string $city
     * @param string $zip
     * @return Collection<string, float>
     * @throws ResponseFailedException
     * @throws CoordinatesNotFoundException
     */
    public function coordinates(string $street, string $number, string $city, string $zip) : Collection
    {
        return $this->driver->coordinates($street, $number, $city, $zip);
    }

    /**
     * Reverse geocode a given coordinate.
     *
     * @param float $longitude
     * @param float $latitude
     * @return Collection<string, string>
     * @throws InvalidCoordinateException
     * @throws ResponseFailedException
     * @throws AddressNotFoundException
     */
    public function address(float $longitude, float $latitude) : Collection
    {
        return $this->driver->address($longitude, $latitude);
    }
}