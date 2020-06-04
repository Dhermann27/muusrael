<?php

namespace App\Http;

use Illuminate\Database\Eloquent\Model;

class Staffposition extends Model
{
    public function compensationlevel()
    {
        return $this->hasOne('App\Http\Compensationlevel', 'id', 'compensationlevel_id');
    }

    public function program()
    {
        return $this->hasOne('App\Http\Program', 'id', 'progrram_id');
    }

    public function assigned()
    {
        return $this->hasMany('App\Http\YearattendingStaff');
    }
}
