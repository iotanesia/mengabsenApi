<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('tujuan_id');
            $table->string('meeting_date')->nullable();
            $table->time('meeting_start')->nullable();
            $table->time('meeting_end')->nullable();
            $table->string('guest_name')->nullable();
            $table->string('company_name')->nullable();
            $table->string('accompanied')->nullable();
            $table->string('detail')->nullable();
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
        Schema::dropIfExists('tasks');
    }
}
