<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Year;
use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(Year::class, function (Faker $faker) {
    $thisyear = $faker->unique()->year;
    return [
        'year' => $thisyear,
        'checkin' => Carbon::parse('first Sunday of July ' . $thisyear)->toDateString(),
        'brochure' => Carbon::parse('first day of February ' . $thisyear)->toDateString(),
        'is_current' => '1',
        'is_live' => '1',
        'is_crunch' => '0',
        'is_accept_paypal' => '1',
        'is_calendar' => '1',
        'is_room_select' => '1',
        'is_workshop_proposal' => '1',
        'is_artfair' => '1',
        'is_coffeehouse' => '0'
    ];
});
