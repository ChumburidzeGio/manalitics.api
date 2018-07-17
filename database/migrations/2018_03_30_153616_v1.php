<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class V1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //$this->external();
        $this->content();
        $this->user();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->dropTables([
            //'references',

            'transactions',

            'users',
            'password_resets',
            'accounts',
            // 'permissions',
            // 'assigned_roles',
            // 'abilities',
            // 'roles',
            // 'activity_log',
        ]);
    }

    public function dropTables($tables)
    {
        array_map(function($table) {
            Schema::dropIfExists($table);
        }, $tables);
    }

    // public function external()
    // {
    //     Schema::create('references', function (Blueprint $table) {
    //         $table->increments('id');
    //         $table->morphs('model');
    //         $table->string('service');
    //         $table->string('reference_id');
    //         $table->timestamps();
    //     });
    // }

    public function content()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 255);
            $table->dateTime('date');
            $table->text('description')->nullable();
            $table->text('note')->nullable();
            $table->integer('amount');
            $table->string('type', 30);
            $table->string('currency', 3);
            $table->string('category', 40);
            $table->boolean('is_expense')->default(true);
            $table->longText('original')->nullable();
            $table->integer('user_id')->unsigned();
            $table->integer('account_id')->unsigned();
            $table->timestamps();
        });
    }

    public function user()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('main_currency', 3)->nullable();
            $table->string('password', 60)->nullable();
            $table->timestamps();
        });

        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token')->index();
            $table->timestamp('created_at');
        });

        Schema::create('accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('tagline')->nullable();
            $table->integer('user_id')->unsigned();
            $table->timestamps();
        });

        // Schema::create('abilities', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->string('name', 150);
        //     $table->string('title')->nullable();
        //     $table->integer('entity_id')->unsigned()->nullable();
        //     $table->string('entity_type', 150)->nullable();
        //     $table->boolean('only_owned')->default(false);
        //     $table->integer('scope')->nullable()->index();
        //     $table->timestamps();
        // });

        // Schema::create('roles', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->string('name', 150);
        //     $table->string('title')->nullable();
        //     $table->integer('level')->unsigned()->nullable();
        //     $table->integer('scope')->nullable()->index();
        //     $table->timestamps();

        //     $table->unique(
        //         ['name', 'scope'],
        //         'roles_name_unique'
        //     );
        // });

        // Schema::create('assigned_roles', function (Blueprint $table) {
        //     $table->integer('role_id')->unsigned()->index();
        //     $table->integer('entity_id')->unsigned();
        //     $table->string('entity_type', 150);
        //     $table->integer('scope')->nullable()->index();

        //     $table->index(
        //         ['entity_id', 'entity_type', 'scope'],
        //         'assigned_roles_entity_index'
        //     );

        //     $table->foreign('role_id')
        //         ->references('id')->on('roles')
        //         ->onUpdate('cascade')->onDelete('cascade');
        // });

        // Schema::create('permissions', function (Blueprint $table) {
        //     $table->integer('ability_id')->unsigned()->index();
        //     $table->integer('entity_id')->unsigned();
        //     $table->string('entity_type', 150);
        //     $table->boolean('forbidden')->default(false);
        //     $table->integer('scope')->nullable()->index();

        //     $table->index(
        //         ['entity_id', 'entity_type', 'scope'],
        //         'permissions_entity_index'
        //     );

        //     $table->foreign('ability_id')
        //         ->references('id')->on('abilities')
        //         ->onUpdate('cascade')->onDelete('cascade');
        // });

        // Schema::create('activity_log', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->string('log_name')->nullable();
        //     $table->text('description');
        //     $table->integer('subject_id')->nullable();
        //     $table->string('subject_type')->nullable();
        //     $table->integer('causer_id')->nullable();
        //     $table->string('causer_type')->nullable();
        //     $table->text('properties')->nullable();
        //     $table->timestamps();

        //     $table->index('log_name');
        // });
    }

    /**
     * Get jsonable column data type.
     *
     * @return string
     */
    // protected function jsonable(): string
    // {
    //     return DB::connection()->getPdo()->getAttribute(PDO::ATTR_DRIVER_NAME) === 'mysql'
    //     && version_compare(DB::connection()->getPdo()->getAttribute(PDO::ATTR_SERVER_VERSION), '5.7.8', 'ge')
    //         ? 'json' : 'text';
    // }
}
