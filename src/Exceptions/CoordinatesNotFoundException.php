<?php

namespace Markuskooche\Geocode\Exceptions;

use Exception;
use Illuminate\Http\Client\Response;

/**
 * The CoordinatesNotFoundException is thrown when the address could not be mapped into coordinates.
 *
 * @author Markus Koch
 * @license MIT
 * @package Markuskooche\Geocode
 */
class CoordinatesNotFoundException extends Exception
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

        parent::__construct("Coordinates not found");
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