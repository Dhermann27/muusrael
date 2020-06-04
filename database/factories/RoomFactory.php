<?php

use App\Http\Building;
use App\Http\Room;
use Faker\Generator as Faker;

$factory->define(Room::class, function (Faker $faker) {
    return [
        'building_id' => function () {
            return factory(Building::class)->create()->id;
        },
        'room_number' => $faker->randomNumber(5),
        'capacity' => $faker->randomNumber(1),
        'is_workshop' => $faker->boolean,
        'is_handicap' => $faker->boolean,
        'xcoord' => $faker->unique()->randomNumber(2) * 6 + 1,
        'ycoord' => $faker->unique()->randomNumber(2) * 6 + 1,
        'pixelsize' => 5
    ];
});
