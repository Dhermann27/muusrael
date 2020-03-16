<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class YearattendingStaff extends Model
{
    public $incrementing = false;
    protected $table = 'yearsattending__staff';

    public function yearsattending()
    {
        return $this->hasOne('App\Yearattending', 'id', 'yearattending_id');
    }

    public function staffposition()
    {
        return $this->hasOne('App\Staffposition', 'id', 'staffposition_id');
    }
}
