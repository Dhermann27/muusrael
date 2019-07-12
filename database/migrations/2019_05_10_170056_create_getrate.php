<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateGetrate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("CREATE FUNCTION getrate(mycamperid INT, myyear YEAR)
                          RETURNS FLOAT DETERMINISTIC
                          BEGIN
                            DECLARE occupants, days, program INT DEFAULT 0;
                            SELECT
                              COUNT(*),
                              MAX(ya.days),
                              MAX(ya.programid)
                            INTO occupants, days, program
                            FROM (campers c, yearsattending ya, yearsattending yap, campers cp)
                            WHERE c.id = mycamperid AND c.id = ya.camperid AND ya.year = myyear AND ya.roomid = yap.roomid
                                  AND ya.year = yap.year AND yap.camperid = cp.id;
                              RETURN (SELECT IFNULL(hr.rate * days, 0)
                                      FROM yearsattending ya, rooms r, rates hr
                                      WHERE ya.camperid = mycamperid AND ya.year = myyear AND r.id = ya.roomid AND
                                            r.buildingid = hr.buildingid AND hr.programid = program AND
                                            occupants >= hr.min_occupancy AND occupants <= hr.max_occupancy AND
                                            myyear >= hr.start_year AND myyear <= hr.end_year);
                          END;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP FUNCTION IF EXISTS getrate;');
    }
}
