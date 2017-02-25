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
            $table->unsignedTinyInteger('request_method')->nullable();
            $table->unsignedInteger('url_path_id')->nullable();
            $table->text('query_string_id')->nullable();
            $table->unsignedInteger('user_agent_id')->nullable();
            $table->string('session_id', 255)->nullable();
            $table->timestamp('created_at');
            $table->index('created_at');
            $table->index(['session_id', 'created_at']);
            $table->index(['url_path_id', 'created_at']);
            $table->foreign('user_agent_id')->references('id')->on('log_user_agents')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('url_path_id')->references('id')->on('log_url_paths')->onUpdate('cascade')->onDelete('set null');
        });

        DB::statement('ALTER TABLE `log_requests` ADD `ip_address` VARBINARY(16) AFTER `user_agent_id`');
        DB::statement('ALTER TABLE `log_requests` ADD INDEX `log_requests_ip_address_created_at_index` (`ip_address`, `created_at`)');
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
