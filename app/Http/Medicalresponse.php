<?php

namespace App\Http;

use Illuminate\Database\Eloquent\Model;

class Medicalresponse extends Model
{
    public function yearattending()
    {
        return $this->hasOne('App\Http\Yearattending');
    }
}
