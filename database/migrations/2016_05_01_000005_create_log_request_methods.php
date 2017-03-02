<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use WebModularity\LaravelLog\LogRequestMethod;

class CreateLogRequestMethods extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_request_methods', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('method', 255)->unique();
        });

        foreach (['GET', 'POST', 'PUT', 'DELETE'] as $method) {
            LogRequestMethod::create(['method' => $method]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('log_request_methods');
    }
}
