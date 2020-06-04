<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Http\Province;
use Faker\Generator as Faker;

$factory->define(Province::class, function (Faker $faker) {
    return [
        'code' => $faker->unique()->regexify('[A-Z]{4}'), //$faker->unique()->stateAbbr,
        'name' => $faker->state
    ];
});
