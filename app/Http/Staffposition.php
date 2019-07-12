<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Staffposition extends Model
{
    public function compensationlevel()
    {
        return $this->hasOne('App\Compensationlevel');
    }

    public function program()
    {
        return $this->hasOne('App\Program');
    }

    public function yearsattending()
    {
        return $this->belongsToMany('App\Yearattending')->using('App\YearattendingStaff');
    }
}
