<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateWorkshopsView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("CREATE VIEW workshops_view AS
            SELECT w.id, room_id, timeslot_id, `order`, name, led_by, blurb, m, t, w, th, f, enrolled, capacity, fee,
                w.created_at, w.updated_at
            FROM workshops w, years y WHERE w.year_id=y.id AND y.is_current=1;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP VIEW IF EXISTS workshops_view;');
    }
}
