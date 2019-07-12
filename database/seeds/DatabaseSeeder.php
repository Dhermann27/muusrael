<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([ChartdaySeeder::class,
            CompensationLevelSeeder::class,
            PronounSeeder::class,
            ProvinceSeeder::class,
            TimeslotSeeder::class]);

        // Admin data

        factory(\App\User::class)->create(['email' => 'dh78@me.com']);
        factory(\App\Year::class)->create();
    }
}
