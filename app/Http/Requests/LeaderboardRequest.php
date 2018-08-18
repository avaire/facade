<?php

namespace App\Http\Requests;

class LeaderboardRequest extends Request
{
    /**
     * The route that should be hit on the endpoint to load our response.
     *
     * @var String
     */
    protected $route = 'leaderboard';

    /**
     * Creates the leaderboard request with the provided guild id.
     *
     * @param String $guildId
     */
    public function __construct($guildId)
    {
        parent::__construct('/' . $guildId);
    }
}
