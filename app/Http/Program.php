<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    public function staffpositions()
    {
        return $this->hasMany('App\Staffposition', 'program_id', 'id');
    }

    public function yearsattending()
    {
        return $this->hasMany('App\Yearattending');
    }
}
