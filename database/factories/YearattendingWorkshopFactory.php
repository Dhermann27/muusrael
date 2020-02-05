<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Workshop;
use App\Yearattending;
use App\YearattendingWorkshop;
use Faker\Generator as Faker;

$factory->define(YearattendingWorkshop::class, function (Faker $faker) {
    return [
        'yearattending_id' => function () {
            return factory(Yearattending::class)->create()->id;
        },
        'workshop_id' => function () {
            return factory(Workshop::class)->create()->id;
        },
    ];
});
