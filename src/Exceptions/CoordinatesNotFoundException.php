<?php

namespace Markuskooche\Geocode\Exceptions;

use Exception;
use Illuminate\Http\Client\Response;

/**
 * The CoordinatesNotFoundException is thrown when the address could not be mapped into coordinates.
 *
 * @author Markus Koch
 * @license MIT
 */
class CoordinatesNotFoundException extends Exception
{
    /* @var Response */
    protected Response $response;

    /**
     * Create a new exception instance.
     *
     * @return void
     */
    public function __construct(Response $response)
    {
        $this->response = $response;

        parent::__construct('Coordinates not found');
    }

    /**
     * Get the response.
     */
    public function getResponse(): Response
    {
        return $this->response;
    }
}
