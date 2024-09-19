<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReimbursTable extends Migration
{
    /**
     * Run the migrations.
     
     * @return void
     */
    public function up()
    {
        Schema::create('reimburs', function (Blueprint $table) {
            $table->id();
            $table->integer("user_id");
            $table->integer("jenis_id");
            $table->string("tujuan_id");
            $table->boolean("status")->nullable();
            $table->string("no_pengembalian");
            $table->date('tgl_pemakaian');
            $table->string('bank');
            $table->string('bukti_path');
            $table->string('bukti_file');
            $table->string('biaya');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reimburs');
    }
}
