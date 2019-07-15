<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Enums\Yearmessage;

class CreateYears extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('years', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('year');
            $table->date('checkin_date');
            $table->date('brochure_date');
            $table->tinyInteger('is_current')->default(0);
            $table->tinyInteger('is_live')->default(0);
            $table->tinyInteger('is_calendar')->default(0);
            $table->tinyInteger('is_crunch')->default(0);
            $table->tinyInteger('is_accept_paypal')->default(0);
            $table->tinyInteger('is_room_select')->default(0);
            $table->tinyInteger('is_workshop_proposal')->default(0);
            $table->tinyInteger('is_artfair')->default(0);
            $table->tinyInteger('is_coffeehouse')->default(0);
            $table->tinyInteger('yearmessage')->default(Yearmessage::CheckinCountdown);
            $table->text('custommessage')->nullable();
            $table->timestamps();
        });
        DB::update('ALTER TABLE years AUTO_INCREMENT = 1000');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('years');
    }
}
