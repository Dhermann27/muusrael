<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ThisyearCharge extends Model
{
    protected $table = "thisyear_charges";

    public function camper()
    {
        return $this->hasOne(Camper::class, 'id', 'camper_id');
    }
}
