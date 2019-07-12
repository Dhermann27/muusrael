<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    public function building()
    {
        return $this->hasOne('App\Building');
    }

    public function program()
    {
        return $this->hasOne('App\Program');
    }
}
