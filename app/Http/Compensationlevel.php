<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Compensationlevel extends Model
{
    public function staffposition()
    {
        return $this->belongsTo('App\Staffposition');
    }

}
