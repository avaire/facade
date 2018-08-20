<?php

namespace App\Http\Discord;

class FakeStatsResponse
{
    /**
     * The amount of fake shards to generate.
     * 
     * @var Integer
     */
    protected $shards;

    /**
     * The keyset that should be generated for the application index.
     * 
     * @var Array
     */
    protected $application = [
        'availableProcessors','memoryFree', 'memoryTotal', 'memoryMax', 'startTime', 'uptime'
    ];

    /**
     * The keyset that should be generated for the global index.
     * 
     * @var Array
     */
    protected $global = [
        'users', 'channels' => ['voice', 'text', 'total'], 'guilds'
    ];

    /**
     * Creates the fake stats response.
     *
     * @param Integer $shards
     */
    public function __construct($shards)
    {
        $this->shards = $shards;
    }

    /**
     * Builds an array with the given keys and the shared value.
     *
     * @param  Array  $keys
     * @param  mixed  $value
     * @param  Array  $arr
     * @return Array
     */
    protected function buildArray($keys, $value = 0, $arr = [])
    {
        foreach ($keys as $key => $item) {
            if (is_array($item)) {
                $arr[$key] = $this->buildArray($item, $value);
            } else {
                $arr[$item] = $value;
            }
        }
        return $arr;
    }

    /**
     * Build the shards ketset.
     * 
     * @return Array
     */
    protected function buildShards()
    {
        $shards = [];
        for ($i = 0; $i < $this->shards; $i++) {
            $shards[$i] = [
                'status' => 'DISCONNECTED',    
                'guilds' => 0,
                'channels' => 0,
                'users' => 0,
                'latency' => 0,
                'id' => $i,
            ];
        }
        return $shards;
    }

    /**
     * Builds the arrays for the response and returns it as an array.
     *
     * @return Array
     */
    public function toArray()
    {
        return [
            'application' => $this->buildArray($this->application),
            'global' => $this->buildArray($this->global),
            'shards' => $this->buildShards(),
            'fake' => true,
        ];
    }
}
