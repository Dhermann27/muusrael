<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class YearattendingWorkshop extends Model
{
    protected $fillable = ['yearattending_id'];
    public $incrementing = false;
    protected $table = 'yearsattending__workshop';

    public function yearattending()
    {
        return $this->hasOne('App\Yearattending', 'id', 'yearattending_id');
    }

    public function workshop()
    {
        return $this->hasOne('App\Workshop', 'id', 'workshop_id');
    }
}
