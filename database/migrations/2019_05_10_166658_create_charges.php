<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCharges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('charges', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('camper_id');
            $table->foreign('camper_id')->references('id')->on('campers');
            $table->float('amount');
            $table->string('order_id')->nullable();
            $table->string('memo')->nullable();
            $table->unsignedBigInteger('chargetype_id');
            $table->foreign('chargetype_id')->references('id')->on('chargetypes');
            $table->date('deposited_date')->nullable();
            $table->date('timestamp')->useCurrent();
            $table->unsignedBigInteger('year_id');
            $table->foreign('year_id')->references('id')->on('years');
            $table->timestamps();
        });
        DB::update('ALTER TABLE charges AUTO_INCREMENT = 1000');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('charges');
    }
}
