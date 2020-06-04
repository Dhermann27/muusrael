<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Http\Pronoun;
use Faker\Generator as Faker;

$factory->define(Pronoun::class, function (Faker $faker) {
    return [
        'code' => $faker->randomLetter,
        'name' => $faker->word
    ];
});
