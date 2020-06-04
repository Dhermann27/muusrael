<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Http\Compensationlevel;
use App\Http\Program;
use App\Http\Staffposition;
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
