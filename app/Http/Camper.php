<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Camper extends Model
{
    public function family()
    {
        return $this->hasOne('App\Family', 'id', 'family_id');
    }

    public function pronoun()
    {
        return $this->hasOne('App\Pronoun', 'id', 'pronoun_id');
    }

    public function foodoption()
    {
        return $this->hasOne('App\Foodoption');
    }

    public function church()
    {
        return $this->hasOne('App\Church', 'id', 'church_id');
    }

    public function yearsattending()
    {
        return $this->hasMany('App\Yearattending');
    }

    public function charges()
    {
        return $this->hasMany('App\Charge');
    }

    public function user() {
        return $this->hasOne('App\User', 'email', 'email');
    }
}
