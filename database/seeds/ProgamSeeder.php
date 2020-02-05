<?php

use App\Enums\Programname;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('programs')->insert([
            ['id' => Programname::Meyer, 'name' => 'Meyer', 'title' => 'Junior High', 'order' => 5, 'is_program_housing' => 1, 'is_minor' => 1],
            ['id' => Programname::Cratty, 'name' => 'Cratty', 'title' => 'Elementary', 'order' => 6, 'is_program_housing' => 0, 'is_minor' => 1],
            ['id' => Programname::Burt, 'name' => 'Burt', 'title' => 'Senior High', 'order' => 4, 'is_program_housing' => 1, 'is_minor' => 1],
            ['id' => Programname::YoungAdultUnderAge, 'name' => 'YA 18-20', 'title' => 'Underage YAs', 'order' => 3, 'is_program_housing' => 0, 'is_minor' => 0],
            ['id' => Programname::Lumens, 'name' => 'Lumens', 'title' => 'Nursery', 'order' => 7, 'is_program_housing' => 0, 'is_minor' => 1],
            ['id' => Programname::Adult, 'name' => 'Adult', 'title' => 'Adult', 'order' => 1, 'is_program_housing' => 0, 'is_minor' => 0],
            ['id' => Programname::YoungAdult, 'name' => 'YA', 'title' => 'YAs', 'order' => 2, 'is_program_housing' => 0, 'is_minor' => 0]
        ]);
    }
}
