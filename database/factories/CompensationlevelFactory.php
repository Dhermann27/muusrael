<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Http\Compensationlevel;
use Faker\Generator as Faker;

$factory->define(Compensationlevel::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'max_compensation' => $faker->randomNumber(3)
    ];
});
