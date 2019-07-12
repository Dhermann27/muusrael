<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Workshop extends Model
{
    public function room()
    {
        return $this->hasOne('App\Room');
    }

    public function timeslot()
    {
        return $this->hasOne('App\Timeslot');
    }

    public function yearsattending()
    {
        return $this->belongsToMany('App\Yearattending')->using('App\YearattendingWorkshop');
    }
}
