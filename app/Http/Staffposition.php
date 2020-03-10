<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Staffposition extends Model
{
    public function compensationlevel()
    {
        return $this->hasOne('App\Compensationlevel', 'id', 'compensationlevel_id');
    }

    public function program()
    {
        return $this->hasOne('App\Program', 'id', 'progrram_id');
    }

    public function yearsattending()
    {
        return $this->belongsToMany('App\Yearattending')->using('App\YearattendingStaff');
    }
}
