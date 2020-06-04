<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Http\Camper;
use App\Http\Family;
use App\Http\Pronoun;
use App\Http\Church;
use App\Http\Foodoption;
use App\Http\Year;
use Faker\Generator as Faker;

$factory->define(Camper::class, function (Faker $faker) {
    $year = date("Y") - Year::where('is_current', '1')->first()->year;
    return [
        'pronoun_id' => function () {
            return factory(Pronoun::class)->create()->id;
        },
        'family_id' => function () {
            return factory(Family::class)->create()->id;
        },
        'firstname' => $faker->firstName,
        'lastname' => $faker->lastName,
        'email' => $faker->safeEmail,
        'phonenbr' => $faker->regexify('[1-9]\d{9}'),
        'birthdate' => $faker->dateTimeBetween('-' . (100 + $year) . ' years', '-' . (19 + $year) . ' years')->format('Y-m-d'),
        'church_id' => function () {
            return factory(Church::class)->create()->id;
        },
        'is_handicap' => 0,
        'foodoption_id' => function () {
            return factory(Foodoption::class)->create()->id;
        }
    ];
});
