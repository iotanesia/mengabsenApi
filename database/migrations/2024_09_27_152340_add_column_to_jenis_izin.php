<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToJenisIzin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('master.mst_jenis_izin', function (Blueprint $table) {
            $table->string("created_by")->nullable();
            $table->string("updated_by")->nullable();
            $table->time("deleted_at")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('master.mst_jenis_izin', function (Blueprint $table) {
            
        });
    }
}
