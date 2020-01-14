<?php

use Illuminate\Database\Migrations\Migration;

class CreateCampersView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Assumes yearsattending with higher id are later
        DB::unprepared("CREATE VIEW campers_view AS
            SELECT c.id, c.family_id, c.pronoun_id, c.firstname, c.lastname, c.email,
              CONCAT(SUBSTR(c.phonenbr, 1, 3), '-', SUBSTR(c.phonenbr, 4, 3), '-', SUBSTR(c.phonenbr, 7, 4)) AS phone,
              c.birthdate, c.roommate, c.sponsor, c.is_handicap, c.foodoption_id, c.church_id,
              (SELECT ya.program_id FROM yearsattending ya WHERE ya.camper_id ORDER BY ya.id DESC LIMIT 1) AS last_program_id
             FROM campers c ORDER BY birthdate");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP VIEW IF EXISTS campers_view;');
    }
}
