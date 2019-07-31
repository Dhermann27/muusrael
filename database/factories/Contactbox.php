<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Contactbox;
use Faker\Generator as Faker;

$factory->define(Contactbox::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'emails' => $faker->safeEmail
    ];
});
