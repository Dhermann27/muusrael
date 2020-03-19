<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChartdataLostcampers extends Model
{
    public function camper()
    {
        return $this->hasOne('App\Camper', 'id', 'camper_id');
    }
}
