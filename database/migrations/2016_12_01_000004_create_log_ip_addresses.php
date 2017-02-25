<?php

use Illuminate\Support\Facades\Schema;
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

        DB::statement('ALTER TABLE `log_ip_addresses` ADD `ip_address` VARBINARY(16) NOT NULL');
        DB::statement('ALTER TABLE `log_ip_addresses` ADD UNIQUE `log_ip_addresses_unique` (`ip_address`)');
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
