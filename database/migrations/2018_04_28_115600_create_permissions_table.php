<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('auth')->hasTable('permissions')) {

            Schema::connection('auth')->create('permissions', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
            });
        }
        if (!Schema::connection('auth')->hasTable('permission_groups')) {
            Schema::connection('auth')->create('permission_groups', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
            });
        }

//        Schema::connection('auth')->create('permission_role', function (Blueprint $table) {
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
//        Schema::connection('auth')->dropIfExists('permission_role');
        Schema::connection('auth')->dropIfExists('permissions');
        Schema::connection('auth')->dropIfExists('permission_groups');
    }
}
