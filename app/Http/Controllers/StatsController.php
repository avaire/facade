<?php

namespace App\Http\Controllers;

use App\Http\Requests\StatsRequest;

class StatsController extends RestController
{
    /**
     * Gets the stats from the API endpoint, the result will
     * be cached for 10 seconds before being refreshed.
     *
     * @return Illuminate\Http\Response
     */
    public function index()
    {
        $response = $this->cacheRequest('stats', 10, new StatsRequest);

        return $this->sendResponse($response);
    }
}
