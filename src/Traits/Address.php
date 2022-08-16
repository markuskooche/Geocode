<?php

namespace Markuskooche\Geocode\Traits;

/**
 * The address trait extends driver with further functions.
 *
 * @author Markus Koch
 * @license MIT
 */
trait Address
{
    /**
     * Transforms the address to a readable string.
     *
     * @param  string  $street
     * @param  string  $number
     * @param  string  $city
     * @param  string  $zip
     * @return string
     */
    private function toReadableAddress(string $street, string $number, string $city, string $zip): string
    {
        return "{$street} {$number}, {$zip} {$city}";
    }
}
