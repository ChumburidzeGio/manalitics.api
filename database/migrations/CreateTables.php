<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTables
{
    public function run()
    {
        $this->dropTables([
            'channels',
            'foreign_ids',

            'properties',
            'room_types',
            'bookable_unit',

            'guests',

            'users',
            'password_resets',
            'permissions',
            'assigned_roles',
            'abilities',
            'roles',
            'activity_log',

            'bookings',
            'booking_rates',
            'booking_prices',
        ]);

        $this->external();
        $this->content();
        $this->guest();
        $this->user();
        $this->shopping();

        // TODO: So we have to add new table rate_plans with columns: id and name
        // TODO: Add column base_rates with columns: price, price1, min_stay, max_stay, max_occupancy
        // TODO: and then to add conditional prices for a weekend f.e. for a certain days or periods of time, occupancy
    }

    public function dropTables($tables)
    {
        array_map(function($table) {
            Schema::dropIfExists($table);
        }, $tables);
    }

    public function external()
    {
        Schema::create('channels', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug');
            $table->string('name');
            $table->string('logo');
            $table->string('description');
            $table->string('website');
            $table->timestamps();
        });

        Schema::create('foreign_ids', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('model');
            $table->string('service');
            $table->string('foreign_id');
            $table->timestamps();
        });
    }

    public function content()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 170);
            $table->string('phone', 20);
            $table->timestamps();
        });

        Schema::create('room_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 170);
            $table->integer('property_id', false, true);
            $table->integer('rooms');
            $table->boolean('is_dormitory')->default(false);
            $table->timestamps();
        });

        Schema::create('bookable_unit', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('room_type_id', false, true);
            $table->enum('type', ['room', 'bed']);
            $table->string('name');
            $table->timestamps();
        });
    }

    public function guest()
    {
        Schema::create('guest', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->integer('property_id', false, true);
            $table->timestamps();
        });
    }

    public function user()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password', 60)->nullable();
            $table->timestamps();
        });

        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token')->index();
            $table->timestamp('created_at');
        });

        Schema::create('abilities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 150);
            $table->string('title')->nullable();
            $table->integer('entity_id')->unsigned()->nullable();
            $table->string('entity_type', 150)->nullable();
            $table->boolean('only_owned')->default(false);
            $table->integer('scope')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 150);
            $table->string('title')->nullable();
            $table->integer('level')->unsigned()->nullable();
            $table->integer('scope')->nullable()->index();
            $table->timestamps();

            $table->unique(
                ['name', 'scope'],
                'roles_name_unique'
            );
        });

        Schema::create('assigned_roles', function (Blueprint $table) {
            $table->integer('role_id')->unsigned()->index();
            $table->integer('entity_id')->unsigned();
            $table->string('entity_type', 150);
            $table->integer('scope')->nullable()->index();

            $table->index(
                ['entity_id', 'entity_type', 'scope'],
                'assigned_roles_entity_index'
            );

            $table->foreign('role_id')
                ->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->integer('ability_id')->unsigned()->index();
            $table->integer('entity_id')->unsigned();
            $table->string('entity_type', 150);
            $table->boolean('forbidden')->default(false);
            $table->integer('scope')->nullable()->index();

            $table->index(
                ['entity_id', 'entity_type', 'scope'],
                'permissions_entity_index'
            );

            $table->foreign('ability_id')
                ->references('id')->on('abilities')
                ->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('activity_log', function (Blueprint $table) {
            $table->increments('id');
            $table->string('log_name')->nullable();
            $table->text('description');
            $table->integer('subject_id')->nullable();
            $table->string('subject_type')->nullable();
            $table->integer('causer_id')->nullable();
            $table->string('causer_type')->nullable();
            $table->text('properties')->nullable();
            $table->timestamps();

            $table->index('log_name');
        });
    }

    public function shopping()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('bookable');
            $table->morphs('user');
            $table->string('currency', 3);
            $table->timestamp('starts_at')->useCurrent();
            $table->timestamp('ends_at')->useCurrent();
            $table->decimal('price')->default('0.00');
            $table->{$this->jsonable()}('price_equation')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('booking_rates', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('bookable');
            $table->tinyInteger('percentage');
            $table->char('operator', 1);
            $table->integer('amount')->unsigned();
            $table->timestamps();
        });

        Schema::create('booking_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('bookable');
            $table->char('weekday', 3);
            $table->time('starts_at');
            $table->time('ends_at');
            $table->tinyInteger('percentage');
            $table->timestamps();
        });
    }

    /**
     * Get jsonable column data type.
     *
     * @return string
     */
    protected function jsonable(): string
    {
        return DB::connection()->getPdo()->getAttribute(PDO::ATTR_DRIVER_NAME) === 'mysql'
        && version_compare(DB::connection()->getPdo()->getAttribute(PDO::ATTR_SERVER_VERSION), '5.7.8', 'ge')
            ? 'json' : 'text';
    }
}