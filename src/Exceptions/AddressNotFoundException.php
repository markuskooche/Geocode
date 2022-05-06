<?php

namespace Markuskooche\Geocode\Exceptions;

use Exception;
use Illuminate\Http\Client\Response;

/**
 * The AddressNotFoundException is thrown when the coordinates could not be mapped to an address.
 *
 * @author Markus Koch
 * @license MIT
 * @package Markuskooche\Geocode
 */
class AddressNotFoundException extends Exception
{
    /* @var Response */
    protected Response $response;

    /**
     * Create a new exception instance.
     *
     * @param Response $response
     * @return void
     */
    public function __construct(Response $response)
    {
        $this->response = $response;

        parent::__construct("Address not found");
    }

    /**
     * Get the response.
     *
     * @return Response
     */
    public function getResponse() : Response
    {
        return $this->response;
    }
}