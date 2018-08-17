<?php

namespace App\Http\Requests;

class VoteRequest extends Request
{
    /**
     * The route that should be hit on the endpoint to load our response.
     *
     * @var String
     */
    protected $route = 'vote';

    /**
     * The request method that should be used for the rest(GET, POST, DELETE).
     *
     * @var String
     */
    protected $requestMethod = 'POST'; 
}
