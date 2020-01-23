<?php

use App\Program;
use App\Yearattending;

$factory->define(Yearattending::class, function () {
    return [
        'days' => 6,
        'program_id' => function () {
            return factory(Program::class)->create()->id;
        }
    ];
});
