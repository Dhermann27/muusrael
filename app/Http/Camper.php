<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Camper extends Model
{
    public function family()
    {
        return $this->hasOne('App\Family');
    }

    public function pronoun()
    {
        return $this->hasOne('App\Pronoun');
    }

    public function foodoption()
    {
        return $this->hasOne('App\Foodoption');
    }

    public function church()
    {
        return $this->hasOne('App\Church');
    }

    public function yearsattending()
    {
        return $this->hasMany('App\Yearattending');
    }

    public function charges()
    {
        return $this->hasMany('App\Charge');
    }
}
