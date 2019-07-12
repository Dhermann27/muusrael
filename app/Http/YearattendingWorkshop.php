<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class YearattendingWorkshop extends Model
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'yearsattending__workshop';

    public function yearattending()
    {
        return $this->hasOne('App\Yearattending');
    }

    public function workshop()
    {
        return $this->hasOne('App\Workshop');
    }
}
