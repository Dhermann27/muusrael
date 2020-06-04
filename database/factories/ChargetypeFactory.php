<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Http\Chargetype;
use Faker\Generator as Faker;

$factory->define(Chargetype::class, function (Faker $faker) {
    return [
        'name' => $faker->company
    ];
});
