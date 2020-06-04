<?php

namespace App\Http;

use Illuminate\Database\Eloquent\Model;

class Timeslot extends Model
{
    protected $dates = ['start_time', 'end_time'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function workshops()
    {
        return $this->hasMany('App\Http\Workshop');
    }

}
