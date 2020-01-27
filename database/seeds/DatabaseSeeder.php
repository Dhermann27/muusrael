<?php

use App\User;
use App\Year;
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
        $this->call([BuildingSeeder::class,
            ChargetypeSeeder::class,
            ChartdaySeeder::class,
            CompensationLevelSeeder::class,
            PronounSeeder::class,
            ProvinceSeeder::class,
            TimeslotSeeder::class]);

        factory(User::class)->create(['email' => 'dh78@me.com']);
    }
}
