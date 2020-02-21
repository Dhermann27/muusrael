<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    public function building()
    {
        return $this->hasOne('App\Building', 'id', 'building_id');
    }

    public function occupants()
    {
        return $this->hasMany('App\ThisyearCamper', 'room_id', 'id');
    }

    public function yearsattending()
    {
        return $this->hasMany('App\Yearsattending');
    }
}
