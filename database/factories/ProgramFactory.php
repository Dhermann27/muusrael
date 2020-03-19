<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Program;
use Faker\Generator as Faker;

$factory->define(Program::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->word,
        'title' => $faker->company,
        'order' => 1,
        'blurb' => $faker->paragraph,
        'letter' => implode('<br />', $faker->paragraphs),
        'covenant' => $faker->paragraph
    ];
});
