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

    /** @var HttpFactory */
    protected HttpFactory $http;

    /** @var string */
    protected string $apiKey;

    /**
     * Instantiate a new HttpFactory instance.
     *
     * @param  string  $apiKey
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
     * @param  string  $street
     * @param  string  $number
     * @param  string  $city
     * @param  string  $zip
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
        if (is_null($results) || count($results) === 0 || $data->get('status') !== 'OK') {
            throw new CoordinatesNotFoundException($response);
        }

        // Initialize the coordinates collection
        $coordinates = new Collection($data['results'][0]['geometry']['location']);

        // Check if the coordinates has all necessary keys
        if (! $coordinates->has($keys)) {
            throw new CoordinatesNotFoundException($response);
        }

        return new Collection([
            'longitude' => $coordinates->get('lng'),
            'latitude' => $coordinates->get('lat'),
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
     * @param  float  $longitude
     * @param  float  $latitude
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
        if (is_null($results) || count($results) === 0 || $data->get('status') !== 'OK') {
            throw new AddressNotFoundException($response);
        }

        // Initialize the coordinates collection
        $address = new Collection($data['results'][0]);

        if (! $address->has('address_components')) {
            throw new AddressNotFoundException($response);
        }

        // Initialize the address components collection
        $addressComponents = $address->get('address_components');

        // Check if the address has minimum required components
        if (count($addressComponents) < 4) {
            throw new AddressNotFoundException($response);
        }

        // Initialize the address variables
        $street = $number = $city = $zip = null;

        // Loop through the address components and fill the variables
        foreach ($addressComponents as $component) {
            $types = $component['types'];
            if (in_array('route', $types)) {
                $street = $component['long_name'];
            } elseif (in_array('street_number', $types)) {
                $number = $component['long_name'];
            } elseif (in_array('locality', $types)) {
                $city = $component['long_name'];
            } elseif (in_array('postal_code', $types)) {
                $zip = $component['long_name'];
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
