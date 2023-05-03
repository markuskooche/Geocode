<?php

namespace Markuskooche\Geocode\Drivers;

use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Support\Collection;
use Markuskooche\Geocode\Exceptions\AddressNotFoundException;
use Markuskooche\Geocode\Exceptions\CoordinatesNotFoundException;
use Markuskooche\Geocode\Exceptions\InvalidCoordinateException;
use Markuskooche\Geocode\Exceptions\ResponseFailedException;
use Markuskooche\Geocode\Traits\Address;
use Markuskooche\Geocode\Traits\Coordinate;

/**
 * The OpenStreetMap class implements the communication with the openstreetmap api.
 *
 * @author Markus Koch
 * @license MIT
 */
class OpenStreetMap implements Driver
{
    use Coordinate, Address;

    protected HttpFactory $http;

    /**
     * Instantiate a new HttpFactory instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->http = new HttpFactory();
    }

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
    public function coordinates(string $street, string $number, string $city, string $zip): Collection
    {
        $response = $this->http->get('https://nominatim.openstreetmap.org/search', [
            'addressdetails' => 1,
            'q' => $this->toReadableAddress($street, $number, $city, $zip),
            'address=' => '',
            'format' => 'jsonv2',
            'limit' => 1,
        ]);

        // Check if the response has failed or is not ok
        if ($response->failed() || ! $response->ok()) {
            throw new ResponseFailedException($response);
        }

        // Initialize necessary variables
        $data = $response->collect();
        $keys = ['lon', 'lat'];

        // Check if the coordinates was found
        if (count($data) === 0) {
            throw new CoordinatesNotFoundException($response);
        }

        // Initialize the coordinates collection
        $collection = new Collection($data[0]);

        // Check if the coordinates has all necessary keys
        if (! $collection->has($keys)) {
            throw new CoordinatesNotFoundException($response);
        }

        return new Collection([
            'longitude' => $collection->get('lon'),
            'latitude' => $collection->get('lat'),
        ]);
    }

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
    public function address(float $longitude, float $latitude): Collection
    {
        $this->checkCoordinate($longitude, $latitude);

        // Request the address from the openstreetmap api
        $response = $this->http->get('https://nominatim.openstreetmap.org/reverse', [
            'lat' => $latitude,
            'lon' => $longitude,
            'format' => 'jsonv2',
        ]);

        // Check if the response has failed or is not ok
        if ($response->failed() || ! $response->ok()) {
            throw new ResponseFailedException($response);
        }

        // Initialize necessary variables
        $data = $response->collect();
        $keys = ['road', 'house_number', 'city', 'postcode'];

        // Check if the address was found
        if ($data->has('error') || ! $data->has('address')) {
            throw new AddressNotFoundException($response);
        }

        // Initialize the address collection
        $collection = new Collection($data->get('address'));

        // Check if the address has all necessary keys
        if (! $collection->has($keys)) {
            throw new AddressNotFoundException($response);
        }

        return new Collection([
            'street' => $collection->get('road'),
            'number' => $collection->get('house_number'),
            'city' => $collection->get('city'),
            'zip' => $collection->get('postcode'),
        ]);
    }
}
