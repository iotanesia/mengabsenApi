<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('auth')->create('users', function (Blueprint $table) {
            $table->id();
            $table->string('app_name')->nullable();
            $table->string('username');
            $table->string('email')->unique();
            $table->string('password');
            $table->text('ip_whitelist')->nullable();
            $table->text('description')->nullable();
            $table->bigInteger('group_id')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
