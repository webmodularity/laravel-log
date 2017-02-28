<?php

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
            $table->unsignedSmallInteger('request_method_id');
            $table->unsignedInteger('ip_address_id');
            $table->unsignedInteger('url_path_id');
            $table->unsignedInteger('user_agent_id');
            $table->string('session_id', 255)->nullable();
            $table->unsignedInteger('query_string_id')->nullable();
            $table->boolean('is_ajax')->default(0);
            $table->timestamp('created_at');
            $table->unique(
                ['request_method_id', 'ip_address_id', 'url_path_id', 'user_agent_id', 'session_id', 'query_string_id'],
                'log_requests_unique'
            );
            $table->index('created_at');
            $table->index(['request_method_id', 'created_at']);
            $table->index(['ip_address_id', 'created_at']);
            $table->index(['url_path_id', 'created_at']);
            $table->index(['user_agent_id', 'created_at']);
            $table->index(['is_ajax', 'created_at']);
            $table->foreign('url_path_id')->references('id')->on('log_url_paths')->onUpdate('cascade');
            $table->foreign('ip_address_id')->references('id')->on('log_ip_addresses')->onUpdate('cascade');
            $table->foreign('user_agent_id')->references('id')->on('log_user_agents')->onUpdate('cascade');
            $table->foreign('query_string_id')->references('id')->on('log_query_strings')->onUpdate('cascade');
            $table->foreign('request_method_id')->references('id')->on('log_request_methods')->onUpdate('cascade');
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
