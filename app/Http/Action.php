<?php

namespace App\Http;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    public function user()
    {
        return $this->hasOne('App\Http\User');
    }
}
