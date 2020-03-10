<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Compensationlevel;
use App\Program;
use App\Staffposition;
use Faker\Generator as Faker;

$factory->define(Staffposition::class, function (Faker $faker) {
    return [
        'name' => $faker->colorName,
        'compensationlevel_id' => function () {
            return factory(Compensationlevel::class)->create()->id;
        },
        'program_id' => function () {
            return factory(Program::class)->create()->id;
        },
        'pctype' => 0
    ];
});
