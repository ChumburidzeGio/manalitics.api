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

    $api->group(['namespace' => 'App\Http\Controllers'], function ($api) {

        $api->group(['prefix' => 'auth/'], function ($api) {
            $api->post('login', 'AuthController@postLogin');
            $api->post('register', 'AuthController@postRegister');
            $api->group(['middleware' => 'api.auth'], function ($api) {
                $api->post('update', 'AuthController@postUpdate');
                $api->post('logout', 'AuthController@deleteInvalidate');
            });
        });

        $api->group(['middleware' => 'api.auth'], function ($api) {
            $api->get('export.toFile', App\Http\Controllers\ExportToFile::class);
            $api->get('stats.general', App\Http\Controllers\StatsGeneral::class);
            $api->get('db.currencies', App\Http\Controllers\Currencies::class);
        });

        $api->get('transactions.all', 'TransactionsController@all');
        $api->get('transactions.find', 'TransactionsController@find');
        $api->post('transactions.update', 'TransactionsController@update');
        $api->post('transactions.importFromFile', 'TransactionsController@importFromFile');
        $api->get('transactions.search', 'TransactionsController@search');

        $api->get('consts.currencies', 'ConstsController@currencies');
        $api->get('consts.banks', 'ConstsController@banks');
        $api->get('consts.categories', 'ConstsController@categories');
        $api->get('consts.transactionTypes', 'ConstsController@transactionTypes');

    });
});