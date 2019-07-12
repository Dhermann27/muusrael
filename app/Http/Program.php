<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    public function yearsattending()
    {
        return $this->hasMany('App\Yearattending');
    }
}
