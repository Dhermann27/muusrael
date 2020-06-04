<?php

namespace App\Http;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    public function thisyearcampers()
    {
        return $this->hasMany('App\Http\ThisyearCamper', 'program_id', 'id')
            ->orderBy('lastname')->orderBy('firstname');
    }

    public function staffpositions()
    {
        return $this->hasMany('App\Http\Staffposition', 'program_id', 'id');
    }

    public function yearsattending()
    {
        return $this->hasMany('App\Http\Yearattending');
    }
}
