<?php

use Spatie\Activitylog\Models\Activity;

use Silber\Bouncer\BouncerFacade;

use Roomify\Bat\Unit\Unit;
use Roomify\Bat\Event\Event;
use Roomify\Bat\Calendar\Calendar;
use Roomify\Bat\Store\SqlDBStore;
use Roomify\Bat\Store\SqlLiteDBStore;
use Roomify\Bat\Constraint\ConstraintManager;
use Roomify\Bat\Constraint\MinMaxDaysConstraint;
use Roomify\Bat\Constraint\CheckInDayConstraint;
use Roomify\Bat\Constraint\DateConstraint;
use Roomify\Bat\Test\SetupStore;
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

    $api->post('import.fromFile', App\Http\Controllers\ImportFromFile::class);
    $api->get('transactions', App\Http\Controllers\Transactions::class);
});