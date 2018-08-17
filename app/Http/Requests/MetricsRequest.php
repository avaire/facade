<?php

namespace App\Http\Requests;

class MetricsRequest extends Request
{
    /**
     * The route that should be hit on the endpoint to load our response.
     *
     * @var String
     */
    protected $route = 'metrics';
}
