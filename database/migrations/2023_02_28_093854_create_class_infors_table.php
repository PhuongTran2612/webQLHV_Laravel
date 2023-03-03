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
        Schema::create('class_infors', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_teachers');
            $table->foreign('id_teachers')->references('id')->on('teachers');
            $table->double('total_money', 10 , 1);
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
        Schema::dropIfExists('class_infors');
    }
};
