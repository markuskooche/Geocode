<?php

namespace Markuskooche\Geocode\Exceptions;

use Exception;
use Illuminate\Http\Client\Response;

/**
 * The ResponseFailedException is thrown when a response returns a failure.
 *
 * @author Markus Koch
 * @license MIT
 * @package Markuskooche\Geocode
 */
class ResponseFailedException extends Exception
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

        parent::__construct("Response Failed");
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