<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Building;
use App\Program;
use App\Rate;
use Faker\Generator as Faker;

$factory->define(Rate::class, function (Faker $faker) {
    return [
        'building_id' => function () {
            return factory(Building::class)->create()->id;
        },
        'program_id' => function () {
            return factory(Program::class)->create()->id;
        },
        'min_occupancy' => 1,
        'max_occupancy' => 999,
        'rate' => $faker->randomFloat(2, 34, 500)
    ];
});
