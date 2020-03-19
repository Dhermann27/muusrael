<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChartdataVeryoldcampers extends Model
{
    public function yearattending()
    {
        return $this->hasOne('App\Yearattending', 'id', 'yearattending_id');
    }
}
