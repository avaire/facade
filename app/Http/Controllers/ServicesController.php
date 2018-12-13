<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\ServicesRequest;

class ServicesController extends RestController
{
    /**
     * Stores the servies that should be displayed in the output, the key should be
     * the Prometheus label instance, and the value should be an object containing
     * the display name, and display label of the service.
     * 
     * @var Object
     */
    protected $services;

    /**
     * Sets up the services that will be used for the index request.
     */
    public function __construct()
    {
        $this->services = require_once __DIR__ . '/../../../config/services.php';
    }

    /**
     * Gets the services from the Prometheus API endpoint, the result
     * will be cached for 10 seconds before being refreshed.
     *
     * @return Illuminate\Http\Response
     */
    public function index()
    {
        $response = $this->cacheRequest('services', 10, new ServicesRequest, function ($request) {
            return [
                'status' => 503,
                'reason' => 'The services API endpoint returnned an invalid response code, service is unavailable.',
            ];
        });

        $services = [];
        foreach ($response['data']['activeTargets'] as $target) {
            $name = $target['labels']['instance'];
            if (! isset($this->services[$name])) {
                continue;
            }

            $services[$this->services[$name]['label']] = [
                'name' => $this->services[$name]['name'],
                'health' => $target['health'],
                'lastError' => strlen($target['lastError']) > 0 ? $target['lastError'] : null,
                'lastScrape' => $target['lastScrape'],
            ];
        }

        return $this->sendResponse($services);
    }
}
