<?php

namespace Markuskooche\Geocode\Traits;

use Markuskooche\Geocode\Exceptions\InvalidCoordinateException;

/**
 * The coordinate trait extends driver with further functions.
 *
 * @author Markus Koch
 * @license MIT
 * @package Markuskooche\Geocode
 */
trait Coordinate
{
    /**
     * Checks if the given longitude and latitude are valid.
     * Throws an InvalidCoordinateException if the longitude or latitude is not valid.
     *
     * @param float $longitude
     * @param float $latitude
     * @throws InvalidCoordinateException
     */
    private function checkCoordinate(float $longitude, float $latitude) : void
    {
        if (($longitude < -180.0 || $longitude > 180.0) || ($latitude < -90.0 || $latitude > 90.0)) {
            throw new InvalidCoordinateException($longitude, $latitude);
        }
    }
}