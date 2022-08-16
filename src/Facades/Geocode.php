<?php

namespace Markuskooche\Geocode\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * The Geocode facade for simple access to the Geocode class.
 *
 * @author Markus Koch
 * @license MIT
 */
class Geocode extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'geocode';
    }
}
