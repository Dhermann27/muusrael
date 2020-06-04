<?php

namespace App\Http;

use Illuminate\Database\Eloquent\Model;

class Compensationlevel extends Model
{
    public function staffposition()
    {
        return $this->belongsTo('App\Http\Staffposition');
    }

}
