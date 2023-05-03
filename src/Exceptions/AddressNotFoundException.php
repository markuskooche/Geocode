<?php

namespace Markuskooche\Geocode\Exceptions;

use Exception;
use Illuminate\Http\Client\Response;

/**
 * The AddressNotFoundException is thrown when the coordinates could not be mapped to an address.
 *
 * @author Markus Koch
 * @license MIT
 */
class AddressNotFoundException extends Exception
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

        parent::__construct('Address not found');
    }

    /**
     * Get the response.
     */
    public function getResponse(): Response
    {
        return $this->response;
    }
}
