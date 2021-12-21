<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilePermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_permission', function (Blueprint $table) {

            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('profile_id');

            $table->primary(['permission_id', 'profile_id']);

            $table->foreign('permission_id')
                ->on('permissions')
                ->references('id')
                ->onDelete('restrict')
                ->onUpdate('restrict');

            $table->foreign('profile_id')
                ->on('profiles')
                ->references('id')
                ->onDelete('restrict')
                ->onUpdate('restrict');

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
        Schema::dropIfExists('profile_permission');
    }
}
