<?php

namespace App\Http\Controllers;

use App\Snapshot;
use Illuminate\Http\Request;
use App\Http\Requests\StatsRequest;
use Illuminate\Support\Facades\Cache;
use App\Http\Discord\FakeStatsResponse;

class StatsController extends RestController
{
    /**
     * The cache token used for caching the amount of
     * shards the API returns in valid responses.
     * 
     * @var String
     */
    protected $cacheToken = 'stats.shard-count';

    /**
     * Gets the stats from the API endpoint, the result will
     * be cached for 10 seconds before being refreshed.
     *
     * @return Illuminate\Http\Response
     */
    public function index()
    {
        $response = $this->cacheRequest('stats', 10, new StatsRequest, function ($request) {
            return (new FakeStatsResponse(
                Cache::get($this->cacheToken, 1)
            ))->toArray();
        });

        if ($this->isValidResponse($response)) {
            Cache::forever($this->cacheToken, count($response['shards']));
        }

        return $this->sendResponse($response);
    }

    /**
     * Loads the global timeseries stats from the cache, or if it doesn't
     * exists, the database will be queried for the data instead.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Pagination\Paginator
     */
    public function timeseries(Request $request)
    {
        return Cache::remember('stats.timeseries.' . $request->get('page', 1), 1, function () {
            return Snapshot::orderBy('id', 'desc')->paginate(4320); // 90 days
        });
    }

    /**
     * Checks if the response is a fake, or a valid response.
     * 
     * @param  Array  $response
     * @return boolean
     */
    protected function isValidResponse($response)
    {
        return ! (isset($response['fake']) && $response['fake']);
    }
}
