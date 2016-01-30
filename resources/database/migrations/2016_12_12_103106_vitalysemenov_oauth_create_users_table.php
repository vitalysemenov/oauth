<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VitalySemenovOauthCreateUsersTable extends Migration
{
    static $table = 'user_oauth';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(static::$table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable();

            $table->string('provider');
            $table->string('uid');
            $table->text('token')->nullable();

            $table->timestamps();

            $table->index('user_id');
            $table->unique(['provider', 'uid']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop(static::$table);
    }
}
