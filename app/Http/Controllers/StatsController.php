<?php

namespace App\Http\Controllers;

use App\Http\Requests\StatsRequest;

class StatsController extends RestController
{
    /**
     * Gets the stats from the API endpoint, the result will
     * be cached for 25 seconds before being refreshed.
     *
     * @return Illuminate\Http\Response
     */
    public function index()
    {
        $response = $this->cacheRequest('stats', 25, new StatsRequest, function () {
            return [
                'status' => 500,
                'reason' => 'Sending an API request to the internal stats endpoint resulted in a failuer.'
            ];
        });

        return $this->sendResponse($response);
    }
}
