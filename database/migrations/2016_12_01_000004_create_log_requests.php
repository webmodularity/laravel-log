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
            $table->unsignedTinyInteger('request_method')->default(1);
            $table->unsignedInteger('ip_address_id');
            $table->unsignedInteger('url_path_id');
            $table->unsignedInteger('user_agent_id');
            $table->string('session_id', 255)->nullable();
            $table->unsignedInteger('query_string_id')->nullable();
            $table->timestamp('created_at');
            $table->unique('request_method', 'ip_address_id', 'url_path_id', 'user_agent_id', 'session_id', 'query_string_id');
            $table->index('created_at');
            $table->index(['ip_address_id', 'created_at']);
            $table->index(['url_path_id', 'created_at']);
            $table->index(['user_agent_id', 'created_at']);
            $table->foreign('url_path_id')->references('id')->on('log_url_paths')->onUpdate('cascade');
            $table->foreign('ip_address_id')->references('id')->on('log_ip_addresses')->onUpdate('cascade');
            $table->foreign('user_agent_id')->references('id')->on('log_user_agents')->onUpdate('cascade');
            $table->foreign('query_string_id')->references('id')->on('log_query_strings')->onUpdate('cascade');
        });
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
