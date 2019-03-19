<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Laravel\Lumen\Routing\Controller;

class RestController extends Controller
{
    /**
     * Build the JSON response from the array response that
     * was returned by the cache, or the request body.
     *
     * @param  Array  $response
     * @param  Integer  $statusCode
     * @return Illuminate\Http\Response
     */
    protected function sendResponse(array $response, $statusCode = 200)
    {
        if (isset($response['status']) && is_integer($response['status'])) {
            $statusCode = $response['status'];
        }
        return new Response($response, $statusCode);
    }

    /**
     * Looks for our key in the cache, if it exists we can return the cached entry, if
     * nothing was found in the cache we send our request, on success we cache and
     * return the response from the request, on failuer, the failuer callback
     * will be invoked and the result of that will be returned instead.
     *
     * @param  String  $key
     * @param  int  $seconds
     * @param  \App\Http\Requests\Request  $request
     * @param  Function  $failuer
     * @param  Boolean  $asArray
     * @return Array|String
     */
    protected function cacheRequest($key, $seconds, Request $request, $failuer = null, $asArray = true)
    {
        if (Cache::has($key)) {
            return $asArray
                ? json_decode(Cache::get($key), true)
                : Cache::get($key);
        }

        try {
            if (! $request->send()->isSuccess()) {
                Log::error("An API request to '{$request->route()}' resulted in an error with status code: {$request->response()->getStatusCode()}\n", [
                    'response' => $request->bodyAsArray()
                ]);

                return $this->handleAndBuildFailuer($request, $failuer, $asArray);
            }

            Cache::put($key, $request->body(), $seconds / 60);

            return $asArray ? $request->bodyAsArray() : $request->body();
        } catch (\Exception $e) {
            return $this->handleAndBuildFailuer($request, $failuer, $asArray);
        }
    }

    /**
     * Formats, builds, and returns the failuer response using
     * the given request, and the failuer object instance.
     * 
     * @param  \App\Http\Requests\Request  $request
     * @param  Function  $failuer
     * @param  Boolean  $asArray
     * @return Array|String
     */
    private function handleAndBuildFailuer($request, $failuer, $asArray)
    {
        $result = $failuer == null || !is_callable($failuer) ? null : $failuer($request);
        if ($result == null) {
            return [
                'status' => 500,
                'reason' => 'Sending an API request to the internal ' . $request->route() . ' endpoint resulted in a failuer.'
            ];
        }

        return $asArray ? $result : json_encode($result);
    }
}
