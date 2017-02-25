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
        Schema::create('log_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('request_method');
            $table->unsignedInteger('url_path_id');
            $table->unsignedInteger('query_string_id');
            $table->unsignedInteger('user_agent_id');
            $table->string('session_id', 255);
            $table->timestamp('created_at');
            $table->index('created_at');
            $table->index(['session_id', 'created_at']);
            $table->index(['url_path_id', 'created_at']);
            $table->foreign('url_path_id')->references('id')->on('log_url_paths')->onUpdate('cascade');
            $table->foreign('user_agent_id')->references('id')->on('log_user_agents')->onUpdate('cascade');
            $table->foreign('query_string_id')->references('id')->on('log_query_strings')->onUpdate('cascade');
        });

        DB::statement('ALTER TABLE `log_requests` ADD `ip_address` VARBINARY(16) NOT NULL AFTER `user_agent_id`');
        DB::statement('ALTER TABLE `log_requests` ADD UNIQUE `log_requests_unique` (`request_method`, `url_path_id`, `query_string_id`, `user_agent_id`, `ip_address`, `session_id`)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('log_requests');
    }
}