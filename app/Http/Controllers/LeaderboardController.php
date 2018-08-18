<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeaderboardRequest;

class LeaderboardController extends RestController
{
    /**
     * Get the leaderboard information for the provided guild
     * ID from the API endpoint, the result will be cached
     * for 2½ minutes before being refreshed.
     *
     * @param  String $guildId
     * @return Illuminate\Http\Response
     */
    public function index($guildId)
    {
        $response = $this->cacheRequest('leaderboard.' . $guildId, 150, new LeaderboardRequest($guildId), function ($request) use ($guildId) {
            if ($request->response()->getStatusCode() == 404) {
                return [
                    'status' => 404,
                    'reason' => 'Invalid guild ID given, no server was found with an ID of "' . $guildId . '"',
                ];
            }
        });

        return $this->sendResponse($response);
    }
}
