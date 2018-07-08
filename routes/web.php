<?php

use Spatie\Activitylog\Models\Activity;

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

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {

    $api->group(['prefix' => 'auth/', 'namespace' => 'App\Http\Controllers\Auth'], function ($api) {
        $api->post('login', 'AuthController@postLogin');
        $api->group(['middleware' => 'api.auth'], function ($api) {
            $api->delete('invalidate', 'AuthController@deleteInvalidate');
            $api->patch('refresh', 'AuthController@patchRefresh');
            $api->get('user', 'AuthController@getUser');
        });
    });

    $api->get('transactions.refresh', App\Http\Controllers\TransactionsRefresh::class);

$api->group([/*'middleware' => 'api.auth'*/], function ($api) {
        $api->get('export.toFile', App\Http\Controllers\ExportToFile::class);
        $api->get('export.toFileParams', App\Http\Controllers\ExportToFileParams::class);
        $api->get('stats.general', App\Http\Controllers\StatsGeneral::class);
        $api->get('transactions', App\Http\Controllers\Transactions::class);
        $api->post('search', App\Http\Controllers\Search::class);
        $api->post('import.fromFile', App\Http\Controllers\ImportFromFile::class);
    });
});