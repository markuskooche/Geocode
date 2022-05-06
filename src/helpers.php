<?php

/**
 * @author Markus Koch
 * @license MIT
 * @package Markuskooche\Geocode
 */


if (! function_exists('float_in_range')) {

    /**
     * Check if two floats are in a given range of epsilon.
     *
     * @param float $a The first float to compare
     * @param float $b The second float to compare
     * @param float $epsilon The epsilon to use for the comparison
     * @return bool True if the two floats are in a range of epsilon, false otherwise
     */
    function float_in_range(float $a, float $b, float $epsilon = 0.01) : bool
    {
        return abs($a - $b) <= $epsilon;
    }
}
