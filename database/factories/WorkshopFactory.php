<?php

use App\Room;
use Faker\Generator as Faker;

$factory->define(App\Workshop::class, function (Faker $faker) {
    return [
        'room_id' => function () {
            return factory(Room::class)->create()->id;
        },
        'order' => $faker->randomNumber(2),
        'name' => $faker->catchPhrase,
        'led_by' => $faker->name,
        'blurb' => $faker->paragraph,
        'm' => $faker->boolean,
        't' => $faker->boolean,
        'w' => $faker->boolean,
        'th' => $faker->boolean,
        'f' => $faker->boolean,
        'capacity' => $faker->randomNumber(2),
        'fee' => $faker->randomFloat(2, 0, 100.0)
    ];
});
