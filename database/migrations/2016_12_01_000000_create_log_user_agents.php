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
            $table->text('user_agent');
        });

        DB::statement('ALTER TABLE `log_user_agents` ADD `user_agent_hash` BINARY(16) NOT NULL, ADD UNIQUE INDEX user_agent_hash_unique (`user_agent_hash`)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('log_user_agents');
    }
}
