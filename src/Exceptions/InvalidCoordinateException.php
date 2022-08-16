<?php

namespace Markuskooche\Geocode\Exceptions;

use Exception;

/**
 * The InvalidCoordinateException is thrown when the given coordinate is invalid.
 *
 * @author Markus Koch
 * @license MIT
 */
class InvalidCoordinateException extends Exception
{
    /* @var float */
    protected float $longitude;

    /* @var float */
    protected float $latitude;

    /**
     * Create a new exception instance.
     *
     * @param  float  $longitude
     * @param  float  $latitude
     * @return void
     */
    public function __construct(float $longitude, float $latitude)
    {
        $this->longitude = $longitude;
        $this->latitude = $latitude;

        parent::__construct("Invalid coordinate. Longitude: {$this->longitude}, Latitude: {$this->latitude}");
    }

    /**
     * Get the longitude.
     *
     * @return float
     */
    public function getLongitude(): float
    {
        return $this->longitude;
    }

    /**
     * Get the latitude.
     *
     * @return float
     */
    public function getLatitude(): float
    {
        return $this->latitude;
    }
}
