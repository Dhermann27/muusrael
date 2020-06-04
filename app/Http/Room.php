<?php

namespace App\Http;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    public function building()
    {
        return $this->hasOne('App\Http\Building', 'id', 'building_id');
    }

    public function occupants()
    {
        return $this->hasMany('App\Http\ThisyearCamper', 'room_id', 'id');
    }

    public function yearsattending()
    {
        return $this->hasMany('App\Http\Yearsattending');
    }
}
