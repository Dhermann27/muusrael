<?php

namespace App\Http;

use Illuminate\Database\Eloquent\Model;

class YearattendingWorkshop extends Model
{
    protected $fillable = ['yearattending_id'];
    public $incrementing = false;
    protected $table = 'yearsattending__workshop';

    public function yearattending()
    {
        return $this->hasOne('App\Http\Yearattending', 'id', 'yearattending_id');
    }

    public function workshop()
    {
        return $this->hasOne('App\Http\Workshop', 'id', 'workshop_id');
    }
}
