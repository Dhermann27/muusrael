<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Family;
use App\Province;
use Faker\Generator as Faker;

$factory->define(Family::class, function (Faker $faker) {
    return [
        'name' => $faker->lastName,
        'address1' => $faker->streetAddress,
        'address2' => $faker->secondaryAddress,
        'city' => $faker->city,
        'province_id' => function () {
            return factory(Province::class)->create()->id;
        },
        'zipcd' => substr($faker->postcode, 0, 5),
        'country' => $faker->country
    ];
});
