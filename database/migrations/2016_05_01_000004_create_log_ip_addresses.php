<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogIpAddresses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_ip_addresses', function (Blueprint $table) {
            $table->increments('id');
        });

        DB::statement('ALTER TABLE `log_ip_addresses` ADD `ip` VARBINARY(16) NOT NULL');
        DB::statement('ALTER TABLE `log_ip_addresses` ADD UNIQUE `log_ip_addresses_unique` (`ip`)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('log_ip_addresses');
    }
}
