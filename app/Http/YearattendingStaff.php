<?php

namespace App\Http;

use Illuminate\Database\Eloquent\Model;

class YearattendingStaff extends Model
{
    public $incrementing = false;
    protected $table = 'yearsattending__staff';

    public function yearsattending()
    {
        return $this->hasOne('App\Http\Yearattending', 'id', 'yearattending_id');
    }

    public function staffposition()
    {
        return $this->hasOne('App\Http\Staffposition', 'id', 'staffposition_id');
    }
}
