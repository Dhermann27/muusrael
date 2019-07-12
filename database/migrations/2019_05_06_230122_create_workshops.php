<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkshops extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workshops', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('year');
            $table->unsignedBigInteger('room_id');
            $table->foreign('room_id')->references('id')->on('rooms');
            $table->unsignedBigInteger('timeslot_id');
            $table->foreign('timeslot_id')->references('id')->on('timeslots');
            $table->integer('order');
            $table->string('name');
            $table->string('led_by')->nullable();
            $table->text('blurb')->nullable();
            $table->tinyInteger('m')->default(0);
            $table->tinyInteger('t')->default(0);
            $table->tinyInteger('w')->default(0);
            $table->tinyInteger('th')->default(0);
            $table->tinyInteger('f')->default(0);
            $table->integer('enrolled')->default(0);
            $table->integer('capacity')->default(0);
            $table->integer('fee')->default(0);
            $table->timestamps();
        });
        DB::update('ALTER TABLE workshops AUTO_INCREMENT = 1000');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workshops');
    }
}
