<?php

use App\Http\Room;
use App\Http\Workshop;
use Faker\Generator as Faker;

$factory->define(Workshop::class, function (Faker $faker) {
    $ref = new ReflectionClass('App\Enums\Timeslotname');
    $slots = $ref->getConstants();

    return [
        'room_id' => function () {
            return factory(Room::class)->create()->id;
        },
        'timeslot_id' => $slots[array_rand($slots)],
        'order' => $faker->randomNumber(2),
        'name' => $faker->catchPhrase,
        'led_by' => $faker->name,
        'blurb' => $faker->paragraph,
        'm' => $faker->boolean,
        't' => $faker->boolean,
        'w' => $faker->boolean,
        'th' => $faker->boolean,
        'f' => $faker->boolean,
        'capacity' => $faker->randomNumber(2) + 1,
        'fee' => $faker->randomFloat(2, 0, 100.0)
    ];
});
