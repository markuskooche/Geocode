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
     */
    private function toReadableAddress(string $street, string $number, string $city, string $zip): string
    {
        return "{$street} {$number}, {$zip} {$city}";
    }
}
