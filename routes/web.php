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
        $api->post('register', 'AuthController@postRegister');
        $api->group(['middleware' => 'api.auth'], function ($api) {
            $api->post('logout', 'AuthController@deleteInvalidate');
            $api->get('user', 'AuthController@getUser');
        });
    });

    $api->get('status', App\Http\Controllers\Status::class);

    $api->group(['middleware' => 'api.auth'], function ($api) {
        $api->get('export.toFile', App\Http\Controllers\ExportToFile::class);
        $api->get('stats.general', App\Http\Controllers\StatsGeneral::class);
        $api->get('transactions', App\Http\Controllers\Transactions::class);
        $api->post('transaction.update', App\Http\Controllers\TransactionUpdate::class);
        $api->post('search', App\Http\Controllers\Search::class);
        $api->post('import.fromFile', App\Http\Controllers\ImportFromFile::class);
    });
});