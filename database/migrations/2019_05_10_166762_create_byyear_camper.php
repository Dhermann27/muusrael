<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateByyearCamper extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("CREATE VIEW byyear_campers AS
                      SELECT
                        y.id                                 year_id,
                        y.year,
                        f.id                                 family_id,
                        f.address1,
                        f.address2,
                        f.city,
                        pv.code                             provincecode,
                        f.zipcd,
                        f.country,
                        f.is_ecomm,
                        f.is_address_current,
                        c.id,
                        c.pronoun_id,
                        o.name                               pronounname,
                        c.firstname,
                        c.lastname,
                        c.email,
                        c.phonenbr,
                        c.birthdate,
                        DATE_FORMAT(c.birthdate, '%m/%d/%Y') birthday,
                        getage(c.birthdate, y.year)         age,
                        p.id                                 program_id,
                        p.name                               programname,
                        p.is_program_housing                 is_program_housing,
                        c.roommate,
                        c.sponsor,
                        c.is_handicap,
                        c.foodoption_id,
                        u.id                                 church_id,
                        u.name                               churchname,
                        u.city                               churchcity,
                        pvp.code                            churchprovincecode,
                        ya.id                                yearattending_id,
                        ya.days,
                        ya.room_id,
                        r.room_number,
                        b.id                                 building_id,
                        b.name                               buildingname
                      FROM (families f, campers c, yearsattending ya, programs p, pronouns o, provinces pv, years y)
                        LEFT JOIN (buildings b, rooms r) ON ya.room_id = r.id AND r.building_id = b.id
                        LEFT JOIN (churches u, provinces pvp) ON c.church_id=u.id AND u.province_id=pvp.id
                      WHERE f.id = c.family_id AND c.id = ya.camper_id AND p.id = ya.program_id AND ya.year_id=y.id
                            AND c.pronoun_id = o.id AND f.province_id=pv.id;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {        DB::unprepared('DROP VIEW IF EXISTS byyear_campers;');
    }
}
