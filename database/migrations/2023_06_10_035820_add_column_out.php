<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnOut extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('absen', function (Blueprint $table) {
            $table->string('long_out')->nullable();
            $table->string('lat_out')->nullable();
            $table->string('address_out')->nullable();
            $table->bigInteger('location_id_out')->nullable();
            $table->string('desc_out')->nullable();
            $table->string('image_out')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('absen', function (Blueprint $table) {
            //
        });
    }
}
