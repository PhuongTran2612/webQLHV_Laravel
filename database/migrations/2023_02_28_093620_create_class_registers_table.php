<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_registers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_students');
            $table->foreign('id_students')->references('id')->on('students');
            $table->unsignedInteger('id_classrooms');
            $table->foreign('id_classrooms')->references('id')->on('classrooms');
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
        Schema::dropIfExists('class_registers');
    }
};
