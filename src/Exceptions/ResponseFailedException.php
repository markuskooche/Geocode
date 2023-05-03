<?php

namespace Markuskooche\Geocode\Exceptions;

use Exception;
use Illuminate\Http\Client\Response;

/**
 * The ResponseFailedException is thrown when a response returns a failure.
 *
 * @author Markus Koch
 * @license MIT
 */
class ResponseFailedException extends Exception
{
    protected Response $response;

    /**
     * Create a new exception instance.
     *
     * @return void
     */
    public function __construct(Response $response)
    {
        $this->response = $response;

        parent::__construct('Response Failed');
    }

    /**
     * Get the response.
     */
    public function getResponse(): Response
    {
        return $this->response;
    }
}
