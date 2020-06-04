<?php

namespace App\Http;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    public function building()
    {
        return $this->hasOne('App\Http\Building');
    }

    public function program()
    {
        return $this->hasOne('App\Http\Program');
    }
}
