<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Http\Church;
use App\Http\Province;
use Faker\Generator as Faker;

$factory->define(Church::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->company,
        'city' => $faker->city,
        'province_id' => function () {
            return factory(Province::class)->create()->id;
        }
    ];
});
