<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\VoteRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\VoteCheckRequest;

class VoteController extends RestController
{
    /**
     * Forwards the vote request to the internal API endpoint of
     * the request has the required form fields and headers.
     *
     * @param  Illuminate\Http\Request  $request
     * @return Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (! $this->isValidRequest($request)) {
            return $this->sendFailedValidationResponse($request);
        }

        return $this->sendResponse((new VoteRequest)->send($request->all(), [
            'Authorization' => $request->headers->get('Authorization')
        ])->bodyAsArray());
    }

    /**
     * Fowards the user IDs to Avas internal API endpoint to check if any
     * of the IDs matches users who have voted in the last 12 hours.
     *
     * @param  string  $userIds
     * @return Illuminate\Http\Response
     */
    public function show($userIds)
    {
        $ids = collect(explode(',', $userIds))->filter(function ($entry) {
            return is_numeric($entry);
        })->implode(',');

        if (strlen($ids) == 0) {
            return $this->sendResponse([
                'status' => 400,
                'reason' => 'Invalid user IDs provided'
            ]);
        }

        $response = $this->cacheRequest('has-voted.' . $ids, 60, new VoteCheckRequest($ids), function ($request) use ($ids) {
            return [
                'status' => 500,
                'reason' => 'Something went wrong while trying to load the voting records for the provided IDs',
            ];
        });

        return $this->sendResponse($response);
    }

    /**
     * Checks if our vote request has all the required fields,
     * headers, and that they're of the right type.
     *
     * @param Illuminate\Http\Request  $request
     * @return Boolean
     */
    protected function isValidRequest(Request $request)
    {
        return $request->headers->has('Authorization')
            && $request->has('user', 'bot', 'type')
            && $request->get('type') == 'upvote';
    }

    /**
     * Sends the failed validation response message, along with
     * logging the request to the log file so debugging.
     *
     * @param Illuminate\Http\Request  $request
     * @return Illuminate\Http\Response
     */
    protected function sendFailedValidationResponse(Request $request)
    {
        $headers = $request->headers->all();
        if (isset($headers['authorization'])) {
            $headers['authorization'] = '-- redacted authorization code --';
        }

        Log::error("A vote request failed the validation check:\n", [
            'paramaters' => $request->all(),
            'headers' => $headers,
        ]);

        return response([
            'reason' => 'Unauthorized request, missing or invalid "Authorization" header give.',
            'status' => 401,
        ], 401);
    }
}
