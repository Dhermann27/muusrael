<?php

use App\Http\Program;
use App\Http\Yearattending;

$factory->define(Yearattending::class, function () {
    return [
        'days' => 6,
        'program_id' => function () {
            return factory(Program::class)->create()->id;
        }
    ];
});
