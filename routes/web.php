<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return redirect()->to('https://avairebot.com/');
});

$router->group(['prefix' => 'v1'], function () use ($router) {
    $router->get('/leaderboard/{guildId}', 'LeaderboardController@index');
    $router->get('/metrics', 'MetricsController@index');
    $router->get('/stats/timeseries', 'StatsController@timeseries');
    $router->get('/stats', 'StatsController@index');
    $router->get('/services', 'ServicesController@index');
    $router->post('/vote', 'VoteController@index');
});
