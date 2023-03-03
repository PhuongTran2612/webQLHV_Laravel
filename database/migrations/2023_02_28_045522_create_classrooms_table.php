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
        Schema::create('classrooms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->date('opening_day');
            $table->unsignedInteger('id_levels');
            $table->foreign('id_levels')->references('id')->on('levels');
            $table->unsignedInteger('id_teachers');
            $table->foreign('id_teachers')->references('id')->on('teachers');
            $table->integer('total');
            $table->integer('actual_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('classrooms');
    }
};
