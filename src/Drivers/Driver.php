<?php

namespace Markuskooche\Geocode\Drivers;

use Illuminate\Support\Collection;
use Markuskooche\Geocode\Exceptions\AddressNotFoundException;
use Markuskooche\Geocode\Exceptions\CoordinatesNotFoundException;
use Markuskooche\Geocode\Exceptions\InvalidCoordinateException;
use Markuskooche\Geocode\Exceptions\ResponseFailedException;

/**
 * The Driver interface defines the methods that must be implemented by a driver.
 *
 * @author Markus Koch
 * @license MIT
 */
interface Driver
{
    /**
     * Geocode a given address.
     * Returns a collection with the following keys:
     * - (float) longitude
     * - (float) latitude
     *
     * @return Collection<string, float>
     *
     * @throws ResponseFailedException
     * @throws CoordinatesNotFoundException
     */
    public function coordinates(string $street, string $number, string $city, string $zip): Collection;

    /**
     * Reverse geocode a given coordinate.
     * Returns a collection with the following keys:
     * - (string) street
     * - (string) number
     * - (string) city
     * - (string) zip
     *
     * @return Collection<string, string>
     *
     * @throws InvalidCoordinateException
     * @throws ResponseFailedException
     * @throws AddressNotFoundException
     */
    public function address(float $longitude, float $latitude): Collection;
}
