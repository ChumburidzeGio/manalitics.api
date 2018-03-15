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

$router->get('/', function () {

    app(CreateTables::class)->run();

    //Channel
    $channel = app(App\Models\Channel::class)->create([
        'slug' => 'booking',
        'name' => 'Booking.com',
        'logo' => '',
        'description' => '',
        'website' => 'https://www.booking.com/'
    ]);

    //Properties
    $property = app(App\Models\Property::class)->create([
        'name' => 'Hotel Tbilisi',
    ]);

    $property->attachExId($channel->slug, 12);

    $property->exIds();

    $property->hasExId($channel->slug, 12);

    $property->removeExId($channel->slug);

    //User
    $user = app(App\Models\User::class)->create([
        'name' => 'Giorgi Chumburidze',
        'email' => 'chumburidze.giorgi@outlook.com',
    ]);

    $user->attachExId('facebook', 1560);

    $user->allow('manage', $property);
    $user->allow('view', $property);

    BouncerFacade::create($user)->can('manage', $property);

    //Room
    $room = $property->rooms()->create([
        'name' => 'Double',
        'price' => "15.25",
        'unit' => 'd',
        'currency' => 'USD',
        'size' => 2
    ]);

    $room->attachExId($channel->slug, 1560);

    //Booking
    $room->newBooking($user, '2017-07-05 12:44:12', '2017-07-10 18:30:11');

    return $room;

    /*$pdo = new \PDO('sqlite::memory:');

    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    // Create tables
    $pdo->exec(SetupStore::createDayTable('availability_event', 'event'));
    $pdo->exec(SetupStore::createDayTable('availability_event', 'state'));
    $pdo->exec(SetupStore::createHourTable('availability_event', 'event'));
    $pdo->exec(SetupStore::createHourTable('availability_event', 'state'));
    $pdo->exec(SetupStore::createMinuteTable('availability_event', 'event'));
    $pdo->exec(SetupStore::createMinuteTable('availability_event', 'state'));

    $constraint = new MinMaxDaysConstraint([], 5, 8);

    $unit = new Unit(1,1, array($constraint));

    $state_store = new SqlLiteDBStore($pdo, 'availability_event', SqlDBStore::BAT_STATE);

    $start_date = new \DateTime('2016-01-01 12:12');
    $end_date = new \DateTime('2016-01-04 07:07');

    $state_event = new Event($start_date, $end_date, $unit, 1); //Event value is 0 (i.e. unavailable)

    $state_calendar = new Calendar(array($unit), $state_store);
    $state_calendar->addEvents(array($state_event), Event::BAT_HOURLY); //BAT_HOURLY denotes granularity


    $s1 = new \DateTime('2016-01-01 00:00');
    $s2 = new \DateTime('2016-01-31 12:00');

    $response = $state_calendar->getMatchingUnits($s1, $s2, array(1), array());

    dd($response);
    return Activity::all();
    activity('default')->log('Look, I logged something');
    app('translator')->setLocale('ka');
    return \App\Models\Property\PropertyType::all();
    return 'Awesomeness is coming shortly!';*/
});

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

    $api->group(['prefix' => 'properties/', 'middleware' => 'api.auth'], function ($api) {
        $api->get('get', App\Http\Controllers\Property\Get::class);
        $api->get('getTypes', App\Http\Controllers\Property\GetTypes::class);
        $api->post('create', App\Http\Controllers\Property\Create::class);
    });

});