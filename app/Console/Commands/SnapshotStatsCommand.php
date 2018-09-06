<?php

namespace App\Console\Commands;

use App\Snapshot;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use App\Http\Requests\StatsRequest;

class SnapshotStatsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'snapshot:stats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Takes a snapshot of the stats and stores it in the database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $stats = new StatsRequest;

        if ($stats->send()->response()->getStatusCode() !== 200) {
            return $this->error('Invalid status code returned from the, exiting process');
        }

        Snapshot::create($this->buildMap(
            json_decode($stats->body())
        ));
    }

    /**
     * Builds the map that should be stored in the database.
     *
     * @param  Object  $data
     * @return Array
     */
    protected function buildMap($data)
    {
        return [
            'guilds'         => $data->global->guilds,
            'channels'       => $data->global->channels->total,
            'channels_text'  => $data->global->channels->text,
            'channels_voice' => $data->global->channels->voice,
            'users'          => $data->global->users,
        ];
    }
}
