<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Charge extends Model
{
    protected $fillable = ['deposited_date'];

    public function camper()
    {
        return $this->hasOne('App\Camper');
    }

    public function chargetype()
    {
        return $this->hasOne('App\Chargetype');
    }
}
