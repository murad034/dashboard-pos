<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('auth')->hasTable('roles')) {
            Schema::connection('auth')->create('roles', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
            });
        }

//        Schema::connection('auth')->create('role_user', function (Blueprint $table) {
//            $table->id();
//            $table->timestamps();
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::connection('mongodb')->dropIfExists('role_user');
        Schema::connection('auth')->dropIfExists('roles');
    }
}
