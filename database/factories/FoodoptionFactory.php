<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Http\Foodoption;
use Faker\Generator as Faker;

$factory->define(Foodoption::class, function (Faker $faker) {
    return [
        'name' => $faker->title
    ];
});
