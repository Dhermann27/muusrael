<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Family;
use App\Province;
use Faker\Generator as Faker;

$factory->define(Family::class, function (Faker $faker) {
    return [
        'address1' => $faker->streetAddress,
        'address2' => $faker->secondaryAddress,
        'city' => $faker->city,
        'province_id' => function () {
            return factory(Province::class)->create()->id;
        },
        'zipcd' => $faker->postcode,
        'country' => $faker->country,
        'is_address_current' => 1,
        'is_ecomm' => rand(0, 1),
        'is_scholar' => 0
    ];
});
