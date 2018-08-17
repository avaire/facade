<?php

namespace App\Http\Requests;

use GuzzleHttp\Client;
use UnexpectedValueException;
use GuzzleHttp\Exception\BadResponseException;

abstract class Request
{
    /**
     * The client that should be used to talk to our API endpoint.
     * 
     * @var GuzzleHttp\Client
     */
    protected $client;

    /**
     * The response from our API request.
     * 
     * @var GuzzleHttp\Exception\BadResponseException|GuzzleHttp\Psr7\Response
     */
    protected $response;

    /**
     * The request method that should be used for the rest(GET, POST, DELETE).
     *
     * @var String
     */
    protected $requestMethod = 'GET'; 

    /**
     * The root endpoint that should be used to talk to the API.
     * 
     * @var String
     */
    protected $endpoint;

    /**
     * The route that should be hit on the endpoint to load our response.
     *
     * @var String
     */
    protected $route = '/';

    /**
     * An array of form paramaters that should be sent along with the request.
     *
     * @var Array
     */
    protected $formParams = [];

    /**
     * An array of headers that should be sent along with the request.
     *
     * @var Array
     */
    protected $headers = [
        'User-Agent' => 'AvaFacade (https://github.com/avaire/facade, 1.0)'
    ];

    public function __construct($method = null)
    {
        $this->endpoint = env('API_ENDPOINT', null);
        if ($this->endpoint == null) {
            throw new UnexpectedValueException('The request API endpoint can not be NULL!');
        }

        $this->client = new Client([
            'base_uri' => $this->endpoint,
            'timeout'  => 3.5,
        ]);

        if ($method !== null) {
            $this->requestMethod = strtoupper($method);
        }
    }

    /**
     * Sends the request to the API endpoint and sets our response.
     * 
     * @return App\Http\Requests\Request
     */
    public function send($param = [], $headers = [])
    {
        try {
            $this->response = $this->client->request($this->requestMethod, $this->route, [
                'body' => json_encode(array_merge($this->formParams, $param)),
                'headers' => array_merge($this->headers, $headers),
            ]);
        } catch (BadResponseException $e) {
            $this->response = $e->getResponse();
        }

        return $this;
    }

    /**
     * Gets our response that was returned from the request.
     *
     * @return GuzzleHttp\Psr7\Response
     */
    public function response()
    {
        return $this->response;
    }

    /**
     * Gets the name of the route that is used for the request.
     *
     * @return String
     */
    public function route()
    {
        return $this->route;
    }

    /**
     * Gets the contents of the body from our request as a string.
     *
     * @return String
     */
    public function body()
    {
        return (string) $this->response->getBody();
    }

    /**
     * Gets the contents of the body from the request as an array.
     *
     * @return Array
     */
    public function bodyAsArray()
    {
        return json_decode($this->body(), true);
    }

    /**
     * Checks to see if our request was a success.
     *
     * @return Boolean
     */
    public function isSuccess()
    {
        if ($this->response == null) {
            return false;
        }

        // We're only looking for status in the range from 200 - 202, since
        // the API will only ever return "OK", "Created", or "Accepted"
        // on success for any of our requests.
        return $this->response->getStatusCode() > 199
            && $this->response->getStatusCode() < 203;
    }

    /**
     * Converts the request to a string by returning the body of the response rquest.
     *
     * @return String
     */
    public function __toString()
    {
        return $this->body();
    }
}
