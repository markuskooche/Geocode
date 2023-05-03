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
 * The GoogleMaps class implements the communication with the google maps api.
 *
 * @author Markus Koch
 * @license MIT
 */
class GoogleMaps implements Driver
{
    use Coordinate, Address;

    protected HttpFactory $http;

    protected string $apiKey;

    /**
     * Instantiate a new HttpFactory instance.
     *
     * @return void
     */
    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
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
        $response = $this->http->get('https://maps.googleapis.com/maps/api/geocode/json', [
            'address' => $this->toReadableAddress($street, $number, $city, $zip),
            'key' => $this->apiKey,
        ]);

        // Check if the response has failed or is not ok
        if ($response->failed() || ! $response->ok()) {
            throw new ResponseFailedException($response);
        }

        // Initialize necessary variables
        $data = $response->collect();
        $keys = ['lng', 'lat'];

        $results = $data->get('results');

        // Check if the coordinates was found
        if (is_null($results) || (is_countable($results) ? count($results) : 0) === 0 || $data->get('status') !== 'OK') {
            throw new CoordinatesNotFoundException($response);
        }

        // Initialize the coordinates collection
        $collection = new Collection($data['results'][0]['geometry']['location']);

        // Check if the coordinates has all necessary keys
        if (! $collection->has($keys)) {
            throw new CoordinatesNotFoundException($response);
        }

        return new Collection([
            'longitude' => $collection->get('lng'),
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
        $response = $this->http->get('https://maps.googleapis.com/maps/api/geocode/json', [
            'latlng' => "{$latitude},{$longitude}",
            'key' => $this->apiKey,
        ]);

        // Check if the response has failed or is not ok
        if ($response->failed() || ! $response->ok()) {
            throw new ResponseFailedException($response);
        }

        // Initialize necessary variables
        $data = $response->collect();
        $results = $data->get('results');

        // Check if the coordinates was found
        if (is_null($results) || (is_countable($results) ? count($results) : 0) === 0 || $data->get('status') !== 'OK') {
            throw new AddressNotFoundException($response);
        }

        // Initialize the coordinates collection
        $collection = new Collection($data['results'][0]);

        if (! $collection->has('address_components')) {
            throw new AddressNotFoundException($response);
        }

        // Initialize the address components collection
        $addressComponents = $collection->get('address_components');

        // Check if the address has minimum required components
        if ((is_countable($addressComponents) ? count($addressComponents) : 0) < 4) {
            throw new AddressNotFoundException($response);
        }

        // Initialize the address variables
        $street = $number = $city = $zip = null;

        // Loop through the address components and fill the variables
        foreach ($addressComponents as $addressComponent) {
            $types = $addressComponent['types'];
            if (in_array('route', $types)) {
                $street = $addressComponent['long_name'];
            } elseif (in_array('street_number', $types)) {
                $number = $addressComponent['long_name'];
            } elseif (in_array('locality', $types)) {
                $city = $addressComponent['long_name'];
            } elseif (in_array('postal_code', $types)) {
                $zip = $addressComponent['long_name'];
            }
        }

        return new Collection([
            'street' => $street,
            'number' => $number,
            'city' => $city,
            'zip' => $zip,
        ]);
    }
}
