<?php

namespace App\Http;

use Illuminate\Database\Eloquent\Model;

class ChartdataVeryoldcampers extends Model
{
    public function yearattending()
    {
        return $this->hasOne('App\Http\Yearattending', 'id', 'yearattending_id');
    }
}
