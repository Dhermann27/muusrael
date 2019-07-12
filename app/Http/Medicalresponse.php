<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Medicalresponse extends Model
{
    public function yearattending()
    {
        return $this->hasOne('App\Yearattending');
    }
}
