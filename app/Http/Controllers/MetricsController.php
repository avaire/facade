<?php

namespace App\Http\Controllers;

use App\Http\Requests\MetricsRequest;

class MetricsController extends RestController
{
    /**
     * Gets the metrics from the API endpoint, the result will
     * be cached for 10 seconds before being refreshed.
     *
     * @return Illuminate\Http\Response
     */
    public function index()
    {
        $response = $this->cacheRequest('metrics', 10, new MetricsRequest, null, false);

        if (json_decode($response, true) !== null) {
            return response($response, 500)->withHeaders([
                'Content-Type' => 'application/json',
            ]);
        }

        return response($response)->withHeaders([
            'Content-Type' => 'text/plain; version=0.0.4; charset=utf-8',
        ]);
    }
}
