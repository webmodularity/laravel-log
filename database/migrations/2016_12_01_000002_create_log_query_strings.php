<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogQueryStrings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_query_strings', function (Blueprint $table) {
            $table->increments('id');
            $table->text('query_string');
        });

        DB::statement('ALTER TABLE `log_query_strings` ADD `query_string_hash` BINARY(16) NOT NULL, ADD UNIQUE INDEX user_agent_hash_unique (`query_string_hash`)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('log_query_strings');
    }
}
