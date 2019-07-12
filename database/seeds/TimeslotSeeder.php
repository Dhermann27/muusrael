<?php

use Illuminate\Database\Seeder;

class TimeslotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $timeslots = array(
            'Sunrise' => [1000, 'SR', '2001-01-01 06:30:00', '2001-01-01 07:30:00'],
            'Morning' => [1001, 'M', '2001-01-01 09:50:00', '2001-01-01 11:50:00'],
            'Early Afternoon' => [1002, 'EA', '2001-01-01 13:30:00', '2001-01-01 15:30:00'],
            'Late Afternoon' => [1003, 'LA', '2001-01-01 16:00:00', '2001-01-01 17:30:00'],
            'Evening' => [1004, 'SS', '2001-01-01 19:30:00', '2001-01-01 20:30:00'],
            'Excursions' => [1005, 'EX', '2001-01-01 00:00:00', '2001-01-01 06:00:00']
        );
        foreach ($timeslots as $name => $info) {
            DB::table('timeslots')->insert([
                'id' => $info[0],
                'name' => $name,
                'code' => $info[1],
                'start_time' => $info[2],
                'end_time' => $info[3]
            ]);
        }
    }
}
