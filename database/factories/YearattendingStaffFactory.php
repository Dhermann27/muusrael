<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Http\Staffposition;
use App\Http\Yearattending;
use App\Http\YearattendingStaff;
use Faker\Generator as Faker;

$factory->define(YearattendingStaff::class, function (Faker $faker) {
    return [
        'yearattending_id' => function () {
            return factory(Yearattending::class)->create()->id;
        },
        'staffposition_id' => function () {
            return factory(Staffposition::class)->create()->id;
        },
    ];
});
