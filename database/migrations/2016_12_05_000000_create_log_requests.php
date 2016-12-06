<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_user_agents', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('user_agent_hash')->unique();
            $table->text('user_agent');
        });

        Schema::create('log_url_paths', function (Blueprint $table) {
            $table->increments('id');
            $table->string('url_path', 255)->unique();
        });

        Schema::create('log_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('url_path_id')->nullable();
            $table->text('url_query_string')->nullable();
            $table->unsignedInteger('user_agent_id')->nullable();
            $table->string('session_id', 255);
            $table->timestamp('created_at');
            $table->foreign('user_agent_id')->references('id')->on('log_user_agents');
            $table->foreign('url_path_id')->references('id')->on('log_url_paths');
        });

        DB::statement('ALTER TABLE log_requests ADD ip_address VARBINARY(16)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('log_requests');
        Schema::drop('log_url_paths');
        Schema::drop('log_user_agents');
    }
}
