<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Camper;
use App\Pronoun;
use App\Church;
use App\Foodoption;
use App\Year;
use Faker\Generator as Faker;

$factory->define(Camper::class, function (Faker $faker) {
    $year = date("Y") - Year::where('is_current', '1')->first()->year;
    return [
        'pronoun_id' => function () {
            return factory(Pronoun::class)->create()->id;
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
