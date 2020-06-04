<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Http\Camper;
use App\Http\Charge;
use App\Http\Chargetype;
use App\Http\Year;
use Faker\Generator as Faker;

$factory->define(Charge::class, function (Faker $faker) {
    return [
        'camper_id' => function () {
            return factory(Camper::class)->create()->id;
        },
        'amount' => $faker->randomNumber(4),
        'memo' => $faker->sentence,
        'chargetype_id' => function () {
            return factory(Chargetype::class)->create()->id;
        },
        'deposited_date' => $faker->date(),
        'timestamp' => $faker->date
    ];
});
