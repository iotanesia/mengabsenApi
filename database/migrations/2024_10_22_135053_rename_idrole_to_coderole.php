<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameIdroleToCoderole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('master.mst_permissions', function (Blueprint $table) {
            $table->dropColumn('id_role');
            $table->string('code_role')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('master.mst_permissions', function (Blueprint $table) {
            //
        });
    }
}
