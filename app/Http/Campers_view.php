<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Campers_view extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'Campers_view';

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
}
