<?php

namespace App\Http\Requests;

class VoteCheckRequest extends Request
{
    /**
     * The route that should be hit on the endpoint to load our response.
     *
     * @var String
     */
    protected $route = 'votes';

    /**
     * Creates the vote request with the provided user id.
     *
     * @param String $userId
     */
    public function __construct($userId)
    {
        parent::__construct('/' . $userId);
    }
}
