<?php

namespace App\Http\Requests;

class ServicesRequest extends Request
{
    /**
     * The route that should be hit on the endpoint to load our response.
     *
     * @var String
     */
    protected $route = 'targets';

    /**
     * Gets the endpointn that should be used for the API requests.
     * 
     * @return String|null
     */
    public function getEndpoint()
    {
        return env('PROMETHEUS_ENNDPOINT', null);
    }
}
